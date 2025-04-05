<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
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
    <title>Admin Dashboard | AgriCycle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <nav class="bg-dark text-white p-3" style="width: 250px; height: 100vh;">
        <h4 class="text-center">Admin Panel</h4>
        <ul class="nav flex-column">
            <li class="nav-item"><a href="admin_dashboard.php" class="nav-link text-white">Dashboard</a></li>
            <li class="nav-item"><a href="manage_users.php" class="nav-link text-white">Manage Users</a></li>
            <li class="nav-item"><a href="view_requests.php" class="nav-link text-white">View Loan/Insurance Requests</a></li>
            <li class="nav-item"><a href="settings.php" class="nav-link text-white">Settings</a></li>
            <li class="nav-item"><a href="../auth/logout.php" class="nav-link text-danger">Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid p-4">
        <h2>Welcome, Admin</h2>
        <p>Manage users, loan applications, and insurance requests.</p>

        <div class="row">
            <div class="col-md-4">
                <div class="card bg-success text-white p-3">
                    <h5>Total Farmers</h5>
                    <h3>
                        <?php 
                        $result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM users WHERE role='farmer'");
                        $row = mysqli_fetch_assoc($result);
                        echo $row['count'];
                        ?>
                    </h3>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-primary text-white p-3">
                    <h5>Total Buyers</h5>
                    <h3>
                        <?php 
                        $result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM users WHERE role='buyer'");
                        $row = mysqli_fetch_assoc($result);
                        echo $row['count'];
                        ?>
                    </h3>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-warning text-dark p-3">
                    <h5>Total Insurance Agents</h5>
                    <h3>
                        <?php 
                        $result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM users WHERE role='insurance_agent'");
                        $row = mysqli_fetch_assoc($result);
                        echo $row['count'];
                        ?>
                    </h3>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
