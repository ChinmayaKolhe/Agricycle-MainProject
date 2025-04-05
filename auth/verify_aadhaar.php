<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer_pending') {
    header("Location: ../auth/login.php");
    exit();
}

$farmer_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['aadhaar_card'])) {
    $aadhaar_path = "../uploads/aadhaar/" . basename($_FILES['aadhaar_card']['name']);
    move_uploaded_file($_FILES['aadhaar_card']['tmp_name'], $aadhaar_path);

    // Store path in DB and set verified = 0 (request sent)
    $update = "UPDATE farmers SET aadhaar_path=?, verification_requested=1 WHERE id=?";
    $stmt = mysqli_prepare($conn, $update);
    mysqli_stmt_bind_param($stmt, "si", $aadhaar_path, $farmer_id);
    mysqli_stmt_execute($stmt);

    $msg = "Your Aadhaar has been submitted for verification. Please wait for admin approval.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aadhaar Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h3 class="text-center text-success">Aadhaar Verification Required</h3>
    <p class="text-center">Upload your Aadhaar to continue using AgriCycle.</p>

    <?php if (isset($msg)) echo "<div class='alert alert-info'>$msg</div>"; ?>

    <form method="POST" enctype="multipart/form-data" class="card p-4 shadow">
        <div class="mb-3">
            <label class="form-label">Upload Aadhaar Card (Image/PDF)</label>
            <input type="file" name="aadhaar_card" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Submit for Verification</button>
    </form>
</body>
</html>
