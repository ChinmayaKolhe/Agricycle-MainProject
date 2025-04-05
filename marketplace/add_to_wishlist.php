<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must log in to add items to the wishlist.'); window.location.href='../login.php';</script>";
    exit;
}

if (isset($_GET['id'])) {
    $user_id = $_SESSION['user_id'];
    $item_id = $_GET['id']; // Correct column name

    // Ensure the item exists in marketplace_items
    $check_product = "SELECT id FROM marketplace_items WHERE id = ?";
    $stmt = $conn->prepare($check_product);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "<script>alert('Error: Product does not exist.'); window.location.href='index.php';</script>";
        exit;
    }

    // Check if already in wishlist
    $check_query = "SELECT * FROM wishlist WHERE user_id = ? AND item_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $user_id, $item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Insert into wishlist
        $insert_query = "INSERT INTO wishlist (user_id, item_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ii", $user_id, $item_id);

        if ($stmt->execute()) {
            echo "<script>alert('Product added to wishlist successfully!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error adding product to wishlist.'); window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('Product is already in your wishlist!'); window.location.href='index.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request! Please try again.'); window.location.href='index.php';</script>";
}
?>