<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer') {
    exit("Unauthorized");
}

$user_email = $_SESSION['email'];

$update_query = "UPDATE notifications SET is_read = 1 WHERE user_email = '$user_email'";
mysqli_query($conn, $update_query);

echo "Success";
?>
