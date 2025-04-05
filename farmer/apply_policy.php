<?php 
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer') {
    header("Location: ../auth/login.php");
    exit();
}

include '../config/db_connect.php';

$farmer_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $policy_id = $_POST['policy_id'] ?? null;
    $agent_id = $_POST['agent_id'] ?? null;

    // Check if both IDs are present
    if (!$policy_id || !$agent_id) {
        $_SESSION['message'] = "❌ Missing policy or agent ID.";
        header("Location: policy_status.php");
        exit();
    }

    // Check if already applied
    $check_query = "SELECT * FROM policy_requests WHERE farmer_id = ? AND policy_id = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    if (!$stmt) {
        $_SESSION['message'] = "❌ Query failed: " . mysqli_error($conn);
        header("Location: policy_status.php");
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'ii', $farmer_id, $policy_id);
    mysqli_stmt_execute($stmt);
    $check_result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['message'] = "⚠️ You have already applied for this policy.";
    } else {
        $insert_query = "INSERT INTO policy_requests (farmer_id, policy_id, agent_id, status) VALUES (?, ?, ?, 'Pending')";
        $stmt = mysqli_prepare($conn, $insert_query);
        if (!$stmt) {
            $_SESSION['message'] = "❌ Insert query failed: " . mysqli_error($conn);
        } else {
            mysqli_stmt_bind_param($stmt, 'iii', $farmer_id, $policy_id, $agent_id);
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['message'] = "✅ Policy request sent successfully!";
            } else {
                $_SESSION['message'] = "❌ Failed to send policy request. Error: " . mysqli_stmt_error($stmt);
            }
        }
    }
}

header("Location: policy_status.php");
exit();
?>
