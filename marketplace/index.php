<?php
session_start();
include '../config/db_connect.php';

// Fetch marketplace items
$query = "SELECT id, item_name, description, price, quantity, contact_info, user_id AS seller_id FROM marketplace_items ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching items: " . mysqli_error($conn));
}

$items = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Marketplace | AgriCycle</title>
    <link rel="stylesheet" href="../marketplace/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

    <?php include '../marketplace/navbar.php'; ?>  

    <div class="container mt-4">
        <h2 class="text-center text-success">Marketplace</h2>

        <div class="row">
            <?php if (empty($items)): ?>
                <p class="text-center">No items available in the marketplace.</p>
            <?php else: ?>
                <?php foreach ($items as $item): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($item['item_name']) ?></h5>
                                <p class="card-text"><strong>Description:</strong> <?= htmlspecialchars($item['description']) ?></p>
                                <p class="card-text"><strong>Price:</strong> ₹<?= htmlspecialchars($item['price']) ?></p>
                                <p class="card-text"><strong>Quantity:</strong> <?= htmlspecialchars($item['quantity']) ?></p>
                                <p class="card-text"><strong>Contact:</strong> <?= htmlspecialchars($item['contact_info']) ?></p>

                                <a href="view_item.php?id=<?= $item['id'] ?>" class="btn btn-primary">View Details</a>

                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'buyer'): ?>
                                    <a href="buy_item.php?id=<?= $item['id'] ?>" class="btn btn-success">Buy Now</a>
                                    <a href="add_to_wishlist.php?id=<?= $item['id'] ?>" class="btn btn-outline-danger">❤ Wishlist</a>
                                <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'farmer' && $_SESSION['user_id'] == $item['seller_id']): ?>
                                    <a href="edit_item.php?id=<?= $item['id'] ?>" class="btn btn-warning">Edit</a>
                                    <a href="delete_item.php?id=<?= $item['id'] ?>" class="btn btn-danger">Delete</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>