<?php
session_start();
include '../config/db_connect.php'; // Database connection

// Fetch listings from the database (Prevent SQL Injection using Prepared Statements)
$query = "SELECT * FROM marketplace_items ORDER BY created_at DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
include '../farmer/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace - AgriCycle</title>
    <link rel="stylesheet" href="../assets/css/marketplace.css">
    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons (if needed) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<!-- Bootstrap JS (for toggling navbar) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>

    <h2>Farm Waste & Recyclable Items Marketplace</h2>
    
    <div class="form-container">
        <h3>List a New Item</h3>
        <form action="add_marketplace_item.php" method="POST">
            <div class="form-group">
                <label>Item Name</label>
                <input type="text" name="item_name" required>
            </div>
            
            <div class="form-group">
                <label>Item Description</label>
                <textarea name="description" rows="3" required></textarea>
            </div>
            
            <div class="form-group">
                <label>Price per kg/unit (₹)</label>
                <input type="number" name="price" required>
            </div>
            
            <div class="form-group">
                <label>Quantity Available (kg)</label>
                <input type="number" name="quantity" required>
            </div>
            
            <div class="form-group">
                <label>Contact Info (Phone/Email)</label>
                <input type="text" name="contact_info" required>
            </div>
            
            <button type="submit">Add Listing</button>
        </form>
    </div>

    <div class="listing-container">
    <h3>Available Listings</h3>
<table border="1">
    <tr>
        <th>Item Name</th>
        <th>Description</th>
        <th>Price (₹)</th>
        <th>Quantity</th>
        <th>Contact</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)) { 
        $contact = htmlspecialchars($row['contact_info']);
        $id = $row['id']; // Assuming each item has an 'id' column in the database
        
        // Check if contact info is phone or email
        if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            $contact_link = "<a href='mailto:$contact'>$contact</a>";
        } elseif (preg_match('/^\+?\d{10,}$/', $contact)) {
            $contact_link = "<a href='tel:$contact'>$contact</a>";
        } else {
            $contact_link = $contact;
        }
    ?>
        <tr>
            <td><?= htmlspecialchars($row['item_name']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><?= htmlspecialchars($row['price']) ?></td>
            <td><?= htmlspecialchars($row['quantity']) ?> kg</td>
            <td><?= $contact_link ?></td>
            <td>
                <a href="edit_marketplace_item.php?id=<?= $id ?>" class="edit-btn">Edit</a>
                <a href="delete_marketplace_item.php?id=<?= $id ?>" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
    <?php } ?>
</table>

    </div>

</body>
</html>
