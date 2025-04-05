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
  <title>Admin Dashboard | AgriCycle</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/admin.css">
  <style>
    body {
      background: linear-gradient(to right, #e8f5e9, #f1f8e9);
      font-family: 'Segoe UI', sans-serif;
    }

    .sidebar {
      background-color: #2e7d32;
      color: white;
      min-height: 100vh;
    }

    .sidebar .nav-link {
      color: white;
      transition: all 0.3s;
    }

    .sidebar .nav-link:hover {
      background-color: #1b5e20;
      border-radius: 5px;
    }

    .card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }

    .card h5 {
      font-weight: bold;
    }

    .card h3 {
      font-size: 2.5rem;
    }
  </style>
</head>
<body>
<div class="d-flex">
  <!-- Sidebar -->
  <nav class="sidebar p-3" style="width: 250px;">
    <h4 class="text-center mb-4">AgriCycle Admin</h4>
    <ul class="nav flex-column">
      <li class="nav-item"><a href="admin_dashboard.php" class="nav-link">Dashboard</a></li>
      <li class="nav-item"><a href="../admin/manage_users.php" class="nav-link">Manage Users</a></li>
      <li class="nav-item"><a href="../admin/view_requests.php" class="nav-link">Loan/Insurance Requests</a></li>
      <li class="nav-item"><a href="../admin/settings.php" class="nav-link">Settings</a></li>
      <li class="nav-item"><a href="../auth/logout.php" class="nav-link text-danger">Logout</a></li>
    </ul>
  </nav>

  <!-- Main content -->
  <div class="container-fluid p-4">
    <h2 class="mb-4">Welcome, Admin</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card bg-success text-white p-4">
          <h5>Total Farmers</h5>
          <h3>
            <?php 
            $res = mysqli_query($conn, "SELECT COUNT(*) AS count FROM farmers");
            $r = mysqli_fetch_assoc($res);
            echo $r['count'];
            ?>
          </h3>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card bg-primary text-white p-4">
          <h5>Total Buyers</h5>
          <h3>
            <?php 
            $res = mysqli_query($conn, "SELECT COUNT(*) AS count FROM buyers");
            $r = mysqli_fetch_assoc($res);
            echo $r['count'];
            ?>
          </h3>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card bg-warning text-dark p-4">
          <h5>Total Insurance Agents</h5>
          <h3>
            <?php 
            $res = mysqli_query($conn, "SELECT COUNT(*) AS count FROM insurance_agents");
            $r = mysqli_fetch_assoc($res);
            echo $r['count'];
            ?>
          </h3>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
