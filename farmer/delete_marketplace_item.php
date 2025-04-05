<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $sql = "DELETE FROM marketplace_items WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        echo "Item deleted successfully!";
    } else {
        echo "Error deleting item: " . mysqli_error($conn);
    }
}

header("Location: marketplace.php");
exit();
?>
