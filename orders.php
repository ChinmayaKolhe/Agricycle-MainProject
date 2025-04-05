<?php
session_start();
include 'config/db_connect.php';

// Only allow buyers
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    echo "<script>alert('Unauthorized access!'); window.location.href = 'auth/login.php';</script>";
    exit;
}

$buyer_id = $_SESSION['user_id'];

// Fetch order details with product info
$query = "
    SELECT 
        o.id AS order_id,
        o.quantity,
        o.total_price,
        o.created_at,
        m.item_name,
        m.description,
        m.price,
        m.contact_info
    FROM orders o
    JOIN marketplace_items m ON o.item_id = m.id
    WHERE o.buyer_id = $buyer_id
    ORDER BY o.created_at DESC
";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Error fetching orders: " . mysqli_error($conn));
}
$orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Orders | AgriCycle</title>
    <link rel="stylesheet" href="marketplace/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<!-- Simple navbar with Home, Marketplace, My Orders, and Logout -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container">
    <a class="navbar-brand" href="index.php">AgriCycle</a>
    <div class="collapse navbar-collapse justify-content-end">
      <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="dashboard/buyer_dashboard.php">🏠 Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="marketplace/index.php">🛒 Marketplace</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="orders.php">📦 My Orders</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-danger" href="auth/logout.php">🚪 Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center text-success mb-4">My Orders</h2>

    <?php if (empty($orders)): ?>
        <p class="text-center">No orders placed yet.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-success">
                    <tr>
                        <th>Item Name</th>
                        <th>Description</th>
                        <th>Price (₹)</th>
                        <th>Quantity</th>
                        <th>Total Cost (₹)</th>
                        <th>Contact</th>
                        <th>Purchased On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['item_name']) ?></td>
                            <td><?= htmlspecialchars($order['description']) ?></td>
                            <td><?= $order['price'] ?></td>
                            <td><?= $order['quantity'] ?></td>
                            <td><?= $order['total_price'] ?></td>
                            <td><?= htmlspecialchars($order['contact_info']) ?></td>
                            <td><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
