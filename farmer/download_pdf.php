<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer') {
    header("Location: ../auth/login.php");
    exit();
}

include '../config/db_connect.php';

if (isset($_GET['id'])) {
    $policy_id = intval($_GET['id']);

    $query = "SELECT pdf_path FROM bank_policies WHERE id = $policy_id LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $file_path = '../' . $row['pdf_path'];

        if (file_exists($file_path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));
            readfile($file_path);
            exit;
        } else {
            echo "❌ File not found in uploads/policies.";
        }
    } else {
        echo "❌ Invalid policy ID.";
    }
} else {
    echo "❌ No policy ID specified.";
}
?>
