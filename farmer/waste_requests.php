<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer') {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/db_connect.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $waste_type = $_POST['waste_type'];
    $quantity = $_POST['quantity'];
    $pickup_available = isset($_POST['pickup_available']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO waste_listings (user_id, waste_type, quantity, pickup_available) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isii", $user_id, $waste_type, $quantity, $pickup_available);

    if ($stmt->execute()) {
        echo "<script>alert('Waste listing added successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Fetch the farmer's waste listings
$query = "SELECT * FROM waste_listings WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

include '../farmer/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Waste Listings | AgriCycle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2>Manage Waste Listings</h2>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Waste Type:</label>
            <select name="waste_type" class="form-control">
    <option value="" selected disabled>Select Waste Type</option>
    <option value="Crop Residue">Crop Residue</option>
    <option value="Animal Manure">Animal Manure</option>
    <option value="Fruit & Vegetable Waste">Fruit & Vegetable Waste</option>
    <option value="Agrochemical Containers">Agrochemical Containers</option>
    <option value="Plastic Mulch">Plastic Mulch</option>
    <option value="Spoiled Grain">Spoiled Grain</option>
    <option value="Weeds & Grass">Weeds & Grass</option>
</select>
        </div>
        <div class="mb-3">
            <label class="form-label">Quantity (Kg):</label>
            <input type="number" name="quantity" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Pickup Available:</label>
            <input type="checkbox" name="pickup_available">
        </div>
        <button type="submit" class="btn btn-success">Add Waste</button>
    </form>

    <h3 class="mt-4">My Waste Listings</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Waste Type</th>
                <th>Quantity (Kg)</th>
                <th>Pickup Available</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['waste_type']) ?></td>
                    <td><?= htmlspecialchars($row['quantity']) ?></td>
                    <td><?= $row['pickup_available'] ? 'Yes' : 'No' ?></td>
                    <td>
                        <a href="delete_waste.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
