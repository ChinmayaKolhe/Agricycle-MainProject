<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer') {
    die("Unauthorized Access");
}

$farmer_id = $_SESSION['user_id'];
$update_query = "UPDATE notifications SET is_read = TRUE WHERE user_id = '$farmer_id'";

if (mysqli_query($conn, $update_query)) {
    echo "Notifications marked as read.";
} else {
    echo "Error updating notifications: " . mysqli_error($conn);
}
?>
