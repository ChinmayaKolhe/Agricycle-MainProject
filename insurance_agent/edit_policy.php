<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'insurance_agent') {
    header("Location: ../auth/login.php");
    exit();
}

require '../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $policy_id = $_POST['policy_id'];
    $new_name = trim($_POST['name']);
    $new_link = trim($_POST['portal_link']);
    $agent_id = $_SESSION['user_id'];

    // Sanitize and validate
    if (!empty($new_name) && filter_var($new_link, FILTER_VALIDATE_URL)) {
        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("UPDATE bank_policies SET name = ?, portal_link = ? WHERE id = ? AND agent_id = ?");
        $stmt->bind_param("ssii", $new_name, $new_link, $policy_id, $agent_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['success'] = "Policy updated successfully.";
        } else {
            $_SESSION['error'] = "No changes made or unauthorized.";
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Invalid input!";
    }
}

$conn->close();
header("Location: active_policies.php");
exit();
?>
