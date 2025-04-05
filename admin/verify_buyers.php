<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/db_connect.php';
include '../utils/send_email.php';

if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    mysqli_query($conn, "UPDATE buyers SET is_verified = 1, verification_requested = 0 WHERE id = $id");

    $res = mysqli_query($conn, "SELECT email, name FROM buyers WHERE id = $id");
    $buyer = mysqli_fetch_assoc($res);
    sendEmail($buyer['email'], "Verification Approved", "Hello <b>{$buyer['name']}</b>,<br>Your identity has been successfully verified. You can now access your dashboard.");
}

if (isset($_GET['reject'])) {
    $id = intval($_GET['reject']);
    $res = mysqli_query($conn, "SELECT email, name, aadhaar_path FROM buyers WHERE id = $id");
    $buyer = mysqli_fetch_assoc($res);
    $aadhaarPath = "../" . $buyer['aadhaar_path'];

    sendEmail($buyer['email'], "Verification Rejected", "Hello <b>{$buyer['name']}</b>,<br>Unfortunately, your identity verification was rejected. Please upload a valid Aadhaar.", $aadhaarPath);

    mysqli_query($conn, "UPDATE buyers SET aadhaar_path = NULL, verification_requested = 0 WHERE id = $id");
}

$result = mysqli_query($conn, "SELECT * FROM buyers WHERE verification_requested = 1 AND is_verified = 0");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Buyer Verification | AgriCycle Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow p-4">
            <h2 class="text-center text-primary mb-4">Pending Buyer Verifications</h2>

            <?php if (mysqli_num_rows($result) == 0): ?>
                <div class="alert alert-info text-center">No buyers pending verification.</div>
            <?php else: ?>
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>Buyer ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Aadhaar</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($buyer = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $buyer['id'] ?></td>
                                <td><?= htmlspecialchars($buyer['name']) ?></td>
                                <td><?= htmlspecialchars($buyer['email']) ?></td>
                                <td>
                                    <?php if (!empty($buyer['aadhaar_path'])): ?>
                                        <a href="../<?= $buyer['aadhaar_path'] ?>" target="_blank" class="btn btn-outline-primary btn-sm">View Aadhaar</a>
                                    <?php else: ?>
                                        Not Uploaded
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="?approve=<?= $buyer['id'] ?>" class="btn btn-success btn-sm me-2">Approve</a>
                                    <a href="?reject=<?= $buyer['id'] ?>" class="btn btn-danger btn-sm">Reject</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
