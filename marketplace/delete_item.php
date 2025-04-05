<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'farmer') {
    die("Access Denied.");
}

if (isset($_GET['id'])) {
    $item_id = intval($_GET['id']);
    $seller_id = $_SESSION['user_id'];

    $query = "DELETE FROM marketplace_items WHERE id = '$item_id' AND user_id = '$seller_id'";
    
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error deleting item: " . mysqli_error($conn);
    }
} else {
    echo "Invalid item ID.";
}
?>
