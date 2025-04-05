<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer') {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/db_connect.php';
include 'header.php';

$farmer_id = $_SESSION['user_id'];

// Fetch farmer details
$query = "SELECT * FROM farmers WHERE id = '$farmer_id'";
$result = mysqli_query($conn, $query);
$farmer = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Farmer Profile | AgriCycle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card mx-auto shadow-lg border-0" style="max-width: 600px;">
        <div class="card-header bg-success text-white text-center">
            <h4 class="mb-0"><i class="bi bi-person-circle"></i> Farmer Profile</h4>
        </div>
        <div class="card-body text-center">

            <!-- Profile Photo -->
            <div class="mb-4">
                <?php if (!empty($farmer['profile_photo']) && file_exists("../" . $farmer['profile_photo'])): ?>
                    <img src="../<?= $farmer['profile_photo'] ?>" alt="Profile Photo" class="rounded-circle shadow" width="120" height="120" style="object-fit: cover;">
                <?php else: ?>
                    <i class="bi bi-person-circle" style="font-size: 5rem; color: gray;"></i>
                <?php endif; ?>
            </div>

            <!-- Profile Info -->
            <p><strong>Name:</strong> <?= htmlspecialchars($farmer['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($farmer['email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($farmer['phone']) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($farmer['location']) ?></p>

            <!-- Edit Button -->
            <a href="edit_profile.php" class="btn btn-outline-success mt-3">
                <i class="bi bi-pencil-square"></i> Edit Profile
            </a>
        </div>
    </div>
</div>
</body>
</html>
