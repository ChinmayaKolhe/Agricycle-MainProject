<?php
session_start();
include '../config/db_connect.php';

if (!isset($_GET['id'])) {
    die("Item not found.");
}

$item_id = intval($_GET['id']);
$query = "SELECT * FROM marketplace_items WHERE id = '$item_id'";
$result = mysqli_query($conn, $query);
$item = mysqli_fetch_assoc($result);

if (!$item) {
    die("Item not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Item | AgriCycle</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<?php include '../marketplace/navbar.php'; ?>

<div class="container mt-4">
    <h2 class="text-center text-success"><?= htmlspecialchars($item['item_name']) ?></h2>
    <p><strong>Description:</strong> <?= htmlspecialchars($item['description']) ?></p>
    <p><strong>Price:</strong> ₹<?= htmlspecialchars($item['price']) ?></p>
    <p><strong>Quantity:</strong> <?= htmlspecialchars($item['quantity']) ?></p>
    <p><strong>Contact:</strong> <?= htmlspecialchars($item['contact_info']) ?></p>
</div>

</body>
</html>
