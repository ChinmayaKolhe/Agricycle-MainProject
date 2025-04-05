<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'insurance_agent') {
    header("Location: ../auth/login.php");
    exit();
}

include '../config/db_connect.php';

if (isset($_GET['id'], $_GET['status'])) {
    $request_id = intval($_GET['id']);
    $new_status = $_GET['status'];

    if (!in_array($new_status, ['Approved', 'Rejected'])) {
        $_SESSION['message'] = "Invalid status.";
        header("Location: policy_requests.php");
        exit();
    }

    $sql = "UPDATE policy_requests SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'si', $new_status, $request_id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = "Status updated to $new_status.";
        } else {
            $_SESSION['message'] = "Error updating status.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['message'] = "Query preparation failed.";
    }
} else {
    $_SESSION['message'] = "Invalid request parameters.";
}

header("Location: policy_requests.php");
exit();
