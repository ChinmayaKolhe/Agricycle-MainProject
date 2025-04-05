<?php 
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'insurance_agent') {
    header("Location: ../auth/login.php");
    exit();
}

include '../config/db_connect.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insurance Agent Dashboard | AgriCycle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        /* Reset body and html */
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            overflow: hidden;
        }

        /* Sidebar */
        nav {
            width: 250px;
            background: #2c3e50;
            color: white;
            height: 100vh;
            padding: 20px;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        nav h4 {
            text-align: center;
        }

        nav ul {
            list-style: none;
            padding: 0;
            flex-grow: 1;
        }

        nav ul li {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 5px;
            transition: background 0.3s;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            display: block;
        }

        nav ul li:hover {
            background: #34495e;
        }

        .text-danger {
            color: #e74c3c !important;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: #f8f9fa;
            width: calc(100% - 250px);
            height: 100vh;
            padding: 20px;
            text-align: center;
        }

        .row {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
        }

        .card {
            width: 220px;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            font-size: 1.2rem;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            font-size: 2rem;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<nav>
    <h4>Insurance Agent Panel</h4>
    <ul>
        <li><a href="insurance_agent_dashboard.php">Dashboard</a></li>
        <li><a href="manage_claims.php">Manage Claims</a></li>
        <li><a href="policy_requests.php">Policy Requests</a></li>
        <li><a href="customer_support.php">Customer Support</a></li>
        <li><a href="../auth/logout.php" class="text-danger">Logout</a></li>
    </ul>
</nav>

<!-- Main Content -->
<div class="main-content">
    <h2>Welcome, Insurance Agent</h2>
    <p>Manage insurance policies, claims, and assist users efficiently.</p>

    <div class="row">
        <div class="card bg-info text-white">
            <h3>30</h3>
            <p>Active Policies</p>
        </div>
        <div class="card bg-warning text-white">
            <h3>12</h3>
            <p>Pending Claims</p>
        </div>
        <div class="card bg-success text-white">
            <h3>8</h3>
            <p>Approved Claims</p>
        </div>
    </div>
</div>

</body>
</html>
