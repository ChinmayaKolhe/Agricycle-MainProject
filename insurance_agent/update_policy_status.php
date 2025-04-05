<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'insurance_agent') {
    http_response_code(403);
    echo "Unauthorized";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['status'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    if (!in_array($status, ['Approved', 'Rejected'])) {
        http_response_code(400);
        echo "Invalid status";
        exit();
    }

    $stmt = mysqli_prepare($conn, "UPDATE policy_requests SET status = ? WHERE id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "si", $status, $id);
        mysqli_stmt_execute($stmt);
        echo "Success";
        exit();
    } else {
        http_response_code(500);
        echo "DB error";
        exit();
    }
}
http_response_code(400);
echo "Invalid request";
?>
