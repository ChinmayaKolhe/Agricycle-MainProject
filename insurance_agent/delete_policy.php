<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'insurance_agent') {
    header("Location: ../auth/login.php");
    exit();
}

include '../config/db_connect.php';

$id = $_GET['id'];
$agent_id = $_SESSION['user_id'];

mysqli_query($conn, "DELETE FROM bank_policies WHERE id='$id' AND agent_id='$agent_id'");
header("Location: active_policies.php");
exit();
