<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer') {
    header("Location: ../auth/login.php");
    exit();
}

include '../config/db_connect.php';

$farmer_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $policy_id = $_POST['policy_id'];
    $agent_id = $_POST['agent_id'];

    // Check if already applied
    $check_query = "SELECT * FROM policy_requests WHERE farmer_id = ? AND policy_id = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, 'ii', $farmer_id, $policy_id);
    mysqli_stmt_execute($stmt);
    $check_result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($check_result) > 0) {
        // Already applied
        $_SESSION['message'] = "You have already applied for this policy.";
    } else {
        // Insert into policy_requests
        $insert_query = "INSERT INTO policy_requests (farmer_id, policy_id, agent_id, status) VALUES (?, ?, ?, 'Pending')";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, 'iii', $farmer_id, $policy_id, $agent_id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = "Application sent successfully!";
        } else {
            $_SESSION['message'] = "Failed to apply. Please try again.";
        }
    }
}

header("Location: policy_request_status.php"); // Optional: redirect to a status page
exit();
?>
