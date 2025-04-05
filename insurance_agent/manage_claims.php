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
  <title>Manage Claims | AgriCycle</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    body {
      background: #f0f4f3;
    }
    nav {
      width: 250px;
      background: linear-gradient(to bottom, #14532d, #065f46);
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
      background-color: #14532d;
      color: white;
    }
    .badge {
      font-size: 0.9rem;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<nav>
  <h4 class="text-center mb-4">Insurance Panel</h4>
  <ul class="nav flex-column gap-3">
    <li class="nav-item"><a href="../dashboard/insurance_agent_dashboard.php"><i class="fa fa-home me-2"></i>Dashboard</a></li>
    <li class="nav-item"><a href="policy_requests.php"><i class="fa fa-file-contract me-2"></i>Policy Requests</a></li>
    <li class="nav-item"><a href="manage_claims.php"><i class="fa fa-tools me-2"></i>Manage Claims</a></li>
    <li class="nav-item"><a href="active_policies.php"><i class="fa fa-folder-open me-2"></i>Active Policies</a></li>
    <li class="nav-item"><a href="bank_policies.php"><i class="fa fa-university me-2"></i>Bank Policies Info</a></li>
<li class="nav-item"><a href="approved_claims.php"><i class="fa fa-check-circle me-2"></i>Approved Claims</a></li>
    <li class="nav-item"><a href="../auth/logout.php" class="text-danger"><i class="fa fa-sign-out-alt me-2"></i>Logout</a></li>
  </ul>
</nav>

<!-- Main Content -->
<div class="main">
  <h3>Manage Insurance Claims</h3>
  <div class="input-group my-3 w-50">
    <input type="text" class="form-control" placeholder="Search Claims...">
    <button class="btn btn-success">Search</button>
  </div>

  <table class="table table-hover">
    <thead>
      <tr>
        <th>Claim ID</th>
        <th>Farmer</th>
        <th>Policy</th>
        <th>Amount</th>
        <th>Status</th>
        <th>Submitted</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <!-- Sample row -->
      <tr>
        <td>#CLM001</td>
        <td>Ravi Kumar</td>
        <td>Drought Coverage</td>
        <td>₹15,000</td>
        <td><span class="badge bg-warning">Pending</span></td>
        <td>2025-04-01</td>
        <td>
          <button class="btn btn-sm btn-success">Approve</button>
          <button class="btn btn-sm btn-danger">Reject</button>
        </td>
      </tr>
      <!-- Add more rows from DB -->
    </tbody>
  </table>
</div>

</body>
</html>
