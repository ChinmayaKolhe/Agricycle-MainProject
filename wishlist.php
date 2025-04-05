<?php
session_start();
include 'config/db_connect.php'; // ✅ Ensure the correct database connection path

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Please login to view your wishlist.");
}

$user_id = $_SESSION['user_id'];

// Fetch wishlist items with full product details
$query = "SELECT mi.id, mi.item_name, mi.description, mi.price, mi.quantity, mi.contact_info 
          FROM wishlist 
          JOIN marketplace_items mi ON wishlist.item_id = mi.id
          WHERE wishlist.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$items = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Wishlist | AgriCycle</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .wishlist-container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }
        .wishlist-card {
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .wishlist-card h5 {
            color: #28a745;
            font-weight: bold;
        }
        .wishlist-card p {
            margin: 5px 0;
        }
        .btn-remove {
            background-color: #dc3545;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-remove:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
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
            <a class="nav-link text-danger" href="auth/logout.php">🚪 Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="wishlist-container">
    <h2 class="text-center text-success">Your Wishlist</h2>

    <?php if (empty($items)): ?>
        <p class="text-center">No items in wishlist.</p>
    <?php else: ?>
        <?php foreach ($items as $item): ?>
            <div class="wishlist-card">
                <h5><?= htmlspecialchars($item['item_name']) ?></h5>
                <p><strong>Description:</strong> <?= htmlspecialchars($item['description']) ?></p>
                <p><strong>Price:</strong> ₹<?= htmlspecialchars($item['price']) ?></p>
                <p><strong>Quantity:</strong> <?= htmlspecialchars($item['quantity']) ?></p>
                <p><strong>Contact:</strong> <?= htmlspecialchars($item['contact_info']) ?></p>
                <a href="remove_from_wishlist.php?id=<?= $item['id'] ?>" class="btn-remove">Remove</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>