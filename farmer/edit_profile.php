<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'farmer') {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/db_connect.php';

$farmer_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    $update_query = "UPDATE farmers SET name='$name', email='$email', phone='$phone', location='$location'";
    
    // Handle profile photo upload
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === 0) {
        $target_dir = "../uploads/farmer_photos/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $filename = "farmer_" . $farmer_id . "_" . basename($_FILES["profile_photo"]["name"]);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
            $photo_path = "uploads/farmer_photos/" . $filename;
            $update_query .= ", profile_photo='$photo_path'";
        }
    }

    $update_query .= " WHERE id = $farmer_id";
    mysqli_query($conn, $update_query);

    header("Location: profile.php");
    exit();
}

$query = "SELECT * FROM farmers WHERE id = $farmer_id";
$result = mysqli_query($conn, $query);
$farmer = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile | AgriCycle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card mx-auto shadow border-0" style="max-width: 650px;">
        <div class="card-header bg-warning text-dark text-center">
            <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Profile</h4>
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <?php if (!empty($farmer['profile_photo'])): ?>
                    <div class="text-center mb-3">
                        <img src="../<?= htmlspecialchars($farmer['profile_photo']) ?>" class="rounded-circle shadow" width="120" height="120" alt="Profile Photo" style="object-fit: cover;">
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($farmer['name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($farmer['email']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($farmer['phone']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($farmer['location']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Profile Photo (optional)</label>
                    <input type="file" name="profile_photo" class="form-control" accept="image/*">
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Update
                    </button>
                    <a href="profile.php" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
