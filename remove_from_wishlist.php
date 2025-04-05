<?php
session_start();
include 'config/db_connect.php'; // ✅ Ensure the correct database connection path

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Please login to manage your wishlist.");
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $item_id = intval($_GET['id']); // Ensure it's a valid integer

    // Delete the item from wishlist
    $query = "DELETE FROM wishlist WHERE user_id = ? AND item_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $item_id);

    if ($stmt->execute()) {
        // Redirect back to wishlist after deletion
        header("Location: wishlist.php?success=Item removed");
        exit();
    } else {
        echo "Error removing item from wishlist.";
    }
} else {
    echo "Invalid request.";
}
?>