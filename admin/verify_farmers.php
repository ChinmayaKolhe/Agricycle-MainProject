<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include '../config/db_connect.php';
include '../utils/send_email.php';

// Approve farmer
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $res = mysqli_query($conn, "SELECT email, name FROM farmers WHERE id = $id");
    $farmer = mysqli_fetch_assoc($res);

    mysqli_query($conn, "UPDATE farmers SET is_verified = 1, verification_requested = 0 WHERE id = $id");

    $subject = "AgriCycle Farmer Verification Approved";
    $message = "<p>Hello <strong>" . htmlspecialchars($farmer['name']) . "</strong>,</p>
                <p>Your account has been <strong>approved</strong> by AgriCycle admin. You can now access the farmer dashboard.</p>
                <p>Thank you for verifying your identity.</p>";

    sendEmail($farmer['email'], $subject, $message);
    header("Location: verify_farmers.php");
    exit();
}

// Reject farmer
if (isset($_GET['reject'])) {
    $id = intval($_GET['reject']);
    $res = mysqli_query($conn, "SELECT email, name, aadhaar_path FROM farmers WHERE id = $id");
    $farmer = mysqli_fetch_assoc($res);

    // Store Aadhaar path before deleting it
    $aadhaarPath = $farmer['aadhaar_path'];

    mysqli_query($conn, "UPDATE farmers SET aadhaar_path = NULL, verification_requested = 0 WHERE id = $id");

    $subject = "AgriCycle Farmer Verification Rejected";
    $message = "<p>Hello <strong>" . htmlspecialchars($farmer['name']) . "</strong>,</p>
                <p>Your account verification was <strong>rejected</strong>. Please ensure you upload a valid Aadhaar card.</p>";

    sendEmail($farmer['email'], $subject, $message, $aadhaarPath);
    header("Location: verify_farmers.php");
    exit();
}

$query = "SELECT * FROM farmers WHERE verification_requested = 1 AND is_verified = 0";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Farmer Verification | AgriCycle Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2 class="mb-4 text-center text-success">Pending Farmer Verifications</h2>

    <?php if (mysqli_num_rows($result) == 0): ?>
        <div class="alert alert-info text-center">No farmers pending verification.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead class="table-success">
                <tr>
                    <th>Farmer ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Aadhaar Card</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($farmer = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $farmer['id'] ?></td>
                        <td><?= htmlspecialchars($farmer['name']) ?></td>
                        <td><?= htmlspecialchars($farmer['email']) ?></td>
                        <td>
                            <?php if (!empty($farmer['aadhaar_path'])): ?>
                                <a href="<?= $farmer['aadhaar_path'] ?>" target="_blank">View Aadhaar</a>
                            <?php else: ?>
                                Not Uploaded
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?approve=<?= $farmer['id'] ?>" class="btn btn-success btn-sm">Approve</a>
                            <a href="?reject=<?= $farmer['id'] ?>" class="btn btn-danger btn-sm">Reject</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
