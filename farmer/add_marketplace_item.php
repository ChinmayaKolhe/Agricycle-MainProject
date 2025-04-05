<?php
session_start();
include '../config/db_connect.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        die("Unauthorized access.");
    }

    $user_id = $_SESSION['user_id']; // Assuming users are logged in

    // Check if all fields are set
    if (!isset($_POST['item_name'], $_POST['description'], $_POST['price'], $_POST['quantity'], $_POST['contact_info'])) {
        die("Error: All fields are required.");
    }

    // Get input values
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $contact_info = mysqli_real_escape_string($conn, $_POST['contact_info']);

    // Insert into database
    $sql = "INSERT INTO marketplace_items (user_id, item_name, description, price, quantity, contact_info) 
            VALUES ('$user_id', '$item_name', '$description', '$price', '$quantity', '$contact_info')";

    if (mysqli_query($conn, $sql)) {
        echo "Listing added successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
