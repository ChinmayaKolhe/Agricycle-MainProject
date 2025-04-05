<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'buyer_pending' && $_SESSION['role'] !== 'buyer')) {
    header("Location: ../auth/login.php");
    exit();
}

include '../config/db_connect.php';
// Redirect verified buyers away from Aadhaar page
$user_id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT is_verified FROM buyers WHERE id = $user_id");
$row = mysqli_fetch_assoc($result);

if ($row['is_verified'] == 1) {
    $_SESSION['role'] = 'buyer';
    header("Location: ../dashboard/buyer_dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['aadhaar'])) {
    $target_dir = "../uploads/aadhaar_buyers/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $filename = "buyer_" . $user_id . "_" . basename($_FILES["aadhaar"]["name"]);
    $target_file = $target_dir . $filename;

    if (move_uploaded_file($_FILES["aadhaar"]["tmp_name"], $target_file)) {
        $db_path = "uploads/aadhaar_buyers/" . $filename;
        mysqli_query($conn, "UPDATE buyers SET aadhaar_path = '$db_path', verification_requested = 1 WHERE id = $user_id");
        $message = '<div class="alert alert-success">Aadhaar uploaded successfully. Please wait for admin approval.</div>';
    } else {
        $message = '<div class="alert alert-danger">Error uploading Aadhaar. Please try again.</div>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buyer Aadhaar Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow rounded p-4 mx-auto" style="max-width: 500px;">
            <h4 class="mb-3 text-center text-success">Buyer Aadhaar Verification</h4>

            <?= $message ?>

            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="aadhaar" class="form-label">Upload Aadhaar Card (PDF or Image)</label>
                    <input type="file" name="aadhaar" id="aadhaar" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>
