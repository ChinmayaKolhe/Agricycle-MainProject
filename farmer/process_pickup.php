<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer') {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/db_connect.php';

$user_id = $_SESSION['user_id'];

// Handle pickup request submission
if (isset($_POST['request_pickup'])) {
    $waste_type = $_POST['waste_type'];
    $quantity = $_POST['quantity'];

    $sql = "INSERT INTO pickup_requests (farmer_id, waste_type, quantity, status) VALUES (?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $user_id, $waste_type, $quantity);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Pickup request submitted successfully!";
    } else {
        $_SESSION['error'] = "Error submitting request.";
    }
    header("Location: pickup_requests.php");
    exit();
}

// Handle pickup cancellation
if (isset($_POST['cancel_pickup'])) {
    $pickup_id = $_POST['pickup_id'];

    $sql = "DELETE FROM pickup_requests WHERE id = ? AND farmer_id = ? AND status = 'Pending'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $pickup_id, $user_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Pickup request cancelled.";
    } else {
        $_SESSION['error'] = "Error cancelling request.";
    }
    header("Location: pickup_requests.php");
    exit();
}
