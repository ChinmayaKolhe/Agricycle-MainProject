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
  <title>Approved Claims | AgriCycle</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    body {
      background-color: #eef8f0;
    }
    nav {
      width: 250px;
      background: linear-gradient(to bottom, #064e3b, #065f46);
      color: white;
      height: 100vh;
      position: fixed;
      top: 0; left: 0;
      padding: 20px;
    }
    nav a {
      color: white;
      text-decoration: none;
    }
    .main {
      margin-left: 270px;
      padding: 40px;
    }
    .table thead {
      background-color: #198754;
      color: white;
    }
    .summary-box {
      background: #d1fae5;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 15px;
      box-shadow: 2px 2px 8px rgba(0,0,0,0.05);
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<nav>
  <h4 class="text-center mb-4">Insurance Panel</h4>
  <ul class="nav flex-column gap-3">
    <li class="nav-item"><a href="../dashboard/insurance_agent_dashboard.php"><i class="fa fa-home me-2"></i>Dashboard</a></li>
    <li class="nav-item"><a href="manage_claims.php"><i class="fa fa-tools me-2"></i>Manage Claims</a></li>
    <li class="nav-item"><a href="policy_requests.php"><i class="fa fa-file-contract me-2"></i>Policy Requests</a></li>
    <li class="nav-item"><a href="active_policies.php"><i class="fa fa-folder-open me-2"></i>Active Policies</a></li>
    <li class="nav-item"><a href="approved_claims.php"><i class="fa fa-check-circle me-2"></i>Approved Claims</a></li>
    <li class="nav-item"><a href="bank_policies.php"><i class="fa fa-university me-2"></i>Bank Policies Info</a></li>
<li class="nav-item"><a href="../auth/logout.php" class="text-danger"><i class="fa fa-sign-out-alt me-2"></i>Logout</a></li>
  </ul>
</nav>

<!-- Main Content -->
<div class="main">
  <div class="summary-box">
    <i class="fa fa-check-circle fa-2x text-success"></i>
    <div>
      <h5>Total Approved Claims</h5>
      <h3>₹1,20,000</h3>
    </div>
    <button class="btn btn-success ms-auto"><i class="fa fa-download"></i> Export PDF</button>
    <button class="btn btn-outline-success"><i class="fa fa-file-excel"></i> Excel</button>
  </div>

  <table class="table table-striped">
    <thead>
      <tr>
        <th>Claim ID</th>
        <th>Farmer</th>
        <th>Policy</th>
        <th>Amount</th>
        <th>Approved On</th>
      </tr>
    </thead>
    <tbody>
      <!-- Sample row -->
      <tr>
        <td>#CLM023</td>
        <td>Asha Patil</td>
        <td>Flood Protection</td>
        <td>₹10,000</td>
        <td>2025-04-02</td>
      </tr>
      <!-- More rows from DB -->
    </tbody>
  </table>
</div>

</body>
</html>
