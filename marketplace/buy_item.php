<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'buyer') {
    die("Access Denied. Only buyers can buy items.");
}

if (!isset($_GET['id'])) {
    die("Item not found.");
}

$item_id = intval($_GET['id']);
$buyer_id = $_SESSION['user_id'];

// Fetch seller_id (which is stored as user_id in marketplace_items)
$seller_query = "SELECT user_id FROM marketplace_items WHERE id = '$item_id'";
$seller_result = mysqli_query($conn, $seller_query);

if ($seller_result && mysqli_num_rows($seller_result) > 0) {
    $row = mysqli_fetch_assoc($seller_result);
    $seller_id = $row['user_id'];

    // Insert order into marketplace_orders
    $query = "INSERT INTO marketplace_orders (item_id, buyer_id, seller_id) VALUES ('$item_id', '$buyer_id', '$seller_id')";

    if (mysqli_query($conn, $query)) {
        // Insert notification AFTER ensuring order is placed
        $notification_msg = "Your item has been purchased by Buyer ID: $buyer_id";
        $notification_query = "INSERT INTO notifications (user_id, message) VALUES ('$seller_id', '$notification_msg')";
        mysqli_query($conn, $notification_query);

        echo "<script>alert('Item purchased successfully!'); window.location='index.php';</script>";
    } else {
        echo "Error purchasing item: " . mysqli_error($conn);
    }
} else {
    die("Seller not found for this item.");
}
?>
