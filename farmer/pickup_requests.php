<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer') {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/db_connect.php';
include 'navbar.php';

$user_id = $_SESSION['user_id'];

// Fetch pickup requests for the logged-in farmer
$sql = "SELECT * FROM pickup_requests WHERE farmer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pickup Requests | AgriCycle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<div class="container mt-4">
    <h2 class="text-primary">Pickup Requests</h2>
    <p>Manage your pickup requests and track their status.</p>

    <!-- Request Pickup Form -->
    <form action="process_pickup.php" method="POST" class="mb-4">
        <div class="mb-3">
            <label for="waste_type" class="form-label">Select Waste Type</label>
            <select name="waste_type" class="form-control">
    <option value="Organic Waste">Organic Waste</option>
    <option value="Crop Residue">Crop Residue</option>
    <option value="Animal Manure">Animal Manure</option>
    <option value="Agrochemical Containers">Agrochemical Containers</option>
    <option value="Plastic Mulch Waste">Plastic Mulch Waste</option>
    <option value="Fruit & Vegetable Waste">Fruit & Vegetable Waste</option>
    <option value="Pesticide Residue Waste">Pesticide Residue Waste</option>
</select>

        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity (kg)</label>
            <input type="number" name="quantity" class="form-control" required>
        </div>
        <button type="submit" name="request_pickup" class="btn btn-success">Request Pickup</button>
    </form>

    <!-- Pickup Requests Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Waste Type</th>
                <th>Quantity (kg)</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['waste_type']) ?></td>
                <td><?= htmlspecialchars($row['quantity']) ?></td>
                <td>
                    <span class="badge bg-<?= $row['status'] == 'Completed' ? 'success' : ($row['status'] == 'Scheduled' ? 'warning' : 'secondary') ?>">
                        <?= htmlspecialchars($row['status']) ?>
                    </span>
                </td>
                <td>
                    <?php if ($row['status'] == 'Pending'): ?>
                        <form action="process_pickup.php" method="POST">
                            <input type="hidden" name="pickup_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="cancel_pickup" class="btn btn-danger btn-sm">Cancel</button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-secondary btn-sm" disabled>No Action</button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
