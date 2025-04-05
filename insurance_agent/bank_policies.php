<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'insurance_agent') {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/db_connect.php';

// Handle add policy form submission
if (isset($_POST['add_policy'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $bank_link = mysqli_real_escape_string($conn, $_POST['bank_link']);
    $agent_id = $_SESSION['user_id'];

    $uploadDir = "../uploads/policies/";
    $pdfPath = "";

    // Handle PDF Upload
    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['pdf_file']['tmp_name'];
        $fileName = basename($_FILES['pdf_file']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($fileExt === 'pdf') {
            $newName = uniqid('policy_', true) . '.' . $fileExt;
            $destination = $uploadDir . $newName;
            if (move_uploaded_file($fileTmp, $destination)) {
                $pdfPath = "uploads/policies/" . $newName;
            }
        }
    }

    $sql = "INSERT INTO bank_policies (agent_id, name, pdf_path, bank_link)
            VALUES ('$agent_id', '$name', '$pdfPath', '$bank_link')";

    if (mysqli_query($conn, $sql)) {
        $success = "Policy added successfully!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Fetch agent's policies
$agent_id = $_SESSION['user_id'];
$policies = mysqli_query($conn, "SELECT * FROM bank_policies WHERE agent_id = '$agent_id'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bank Policies | Insurance Agent - AgriCycle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f2f6f7;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            max-width: 900px;
            margin-top: 40px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .form-control, .btn {
            border-radius: 8px;
        }

        .policy-card h5 {
            color: #1e7e34;
            font-weight: bold;
        }

        .btn-pdf {
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Add New Bank Policy</h2>
    <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <form method="POST" enctype="multipart/form-data" class="card p-4 mb-5">
        <div class="mb-3">
            <label class="form-label">Policy Title</label>
            <input type="text" class="form-control" name="name" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Bank Portal Link</label>
            <input type="url" class="form-control" name="bank_link" placeholder="https://..." required>
        </div>

        <div class="mb-3">
            <label class="form-label">Attach PDF File</label>
            <input type="file" name="pdf_file" accept=".pdf" class="form-control" required>
        </div>

        <button type="submit" name="add_policy" class="btn btn-success w-100">
            <i class="fa fa-plus"></i> Submit Policy
        </button>
    </form>

    <h4 class="mb-3">Your Uploaded Policies</h4>
    <div class="row">
        <?php while ($row = mysqli_fetch_assoc($policies)) { ?>
            <div class="col-md-6 mb-4">
                <div class="card p-3 policy-card">
                    <h5><?php echo htmlspecialchars($row['name']); ?></h5>
                    <a href="<?php echo htmlspecialchars($row['bank_link']); ?>" target="_blank" class="d-block mb-2 text-decoration-underline">
                        Bank Portal Link
                    </a>
                    <?php if (!empty($row['pdf_path'])) { ?>
                        <a href="../<?php echo $row['pdf_path']; ?>" class="btn btn-outline-primary btn-sm btn-pdf" target="_blank">
                            <i class="fa fa-file-pdf"></i> View PDF
                        </a>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

</body>
</html>
