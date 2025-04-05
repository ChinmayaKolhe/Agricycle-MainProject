<?php
$servername = "localhost";
$username = "root";
$password = "";  // Default for XAMPP
$dbname = "agricycle";
 // Add this line to specify the new port

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
