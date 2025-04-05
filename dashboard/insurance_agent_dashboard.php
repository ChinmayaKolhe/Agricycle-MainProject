<?php  
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'insurance_agent') {
    header("Location: ../auth/login.php");
    exit();
}

include '../config/db_connect.php';
// Fetch pending policy requests
$pendingRequestsQuery = "SELECT COUNT(*) as total FROM policy_requests WHERE status = 'pending'";
$pendingRequestsResult = mysqli_query($conn, $pendingRequestsQuery);
$pendingRequests = mysqli_fetch_assoc($pendingRequestsResult)['total'];

// Fetch banking policies count
$bankPoliciesQuery = "SELECT COUNT(*) as total FROM bank_policies";
$bankPoliciesResult = mysqli_query($conn, $bankPoliciesQuery);
$bankPolicies = mysqli_fetch_assoc($bankPoliciesResult)['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Insurance Agent Dashboard | AgriCycle</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: #f0f4f3;
      color: #2c3e50;
    }

    nav {
      width: 250px;
      background: linear-gradient(135deg, #14532d, #22c55e);
      color: white;
      height: 100vh;
      padding: 20px;
      position: fixed;
      top: 0;
      left: 0;
      box-shadow: 3px 0 8px rgba(0,0,0,0.1);
    }

    nav h4 {
      text-align: center;
      margin-bottom: 30px;
      font-weight: bold;
    }

    nav ul {
      list-style: none;
      padding: 0;
    }

    nav ul li {
      margin-bottom: 20px;
    }

    nav ul li a {
      text-decoration: none;
      color: white;
      font-size: 1rem;
      padding: 10px;
      display: block;
      border-radius: 5px;
      transition: background 0.3s ease-in-out;
    }

    nav ul li a:hover {
      background: rgba(255,255,255,0.15);
    }

    .main-content {
      margin-left: 250px;
      padding: 30px;
    }

    .dashboard-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 40px;
    }

    .dashboard-header h2 {
      font-weight: 600;
    }

    .cards {
      display: flex;
      gap: 30px;
      flex-wrap: wrap;
      justify-content: center;
    }

    .card-box {
      background: white;
      border-radius: 15px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
      padding: 30px;
      width: 260px;
      text-align: center;
      transition: transform 0.4s ease;
      position: relative;
      overflow: hidden;
    }

    .card-box:hover {
      transform: translateY(-8px);
    }

    .card-box i {
      font-size: 2rem;
      margin-bottom: 10px;
      color: #16a34a;
      transition: transform 0.3s ease;
    }

    .card-box:hover i {
      transform: scale(1.2);
    }

    .card-title {
      font-size: 1.2rem;
      font-weight: 600;
    }

    .card-count {
      font-size: 2.5rem;
      font-weight: bold;
      color: #14532d;
    }

    .badge-pulse {
      position: absolute;
      top: 15px;
      right: 15px;
      background: #22c55e;
      color: white;
      padding: 5px 12px;
      border-radius: 30px;
      font-size: 0.8rem;
      animation: pulse 1.8s infinite;
    }

    @keyframes pulse {
      0% { box-shadow: 0 0 0 0 rgba(34,197,94, 0.6); }
      70% { box-shadow: 0 0 0 15px rgba(34,197,94, 0); }
      100% { box-shadow: 0 0 0 0 rgba(34,197,94, 0); }
    }

    @media (max-width: 768px) {
      nav {
        display: none;
      }

      .main-content {
        margin: 0;
      }
    }
  </style>
</head>
<body>
<?php include '../insurance_agent/insurance_agent_navbar.php'; ?>

<nav>
  <h4>Agent Panel</h4>
  <ul>
    <li><a href="insurance_agent_dashboard.php"><i class="fa-solid fa-chart-line me-2"></i> Dashboard</a></li>
    <li><a href="../insurance_agent/policy_request.php"><i class="fa-solid fa-file-circle-plus me-2"></i> Policy Requests</a></li>
    <li><a href="../insurance_agent/bank_policies.php"><i class="fa-solid fa-file-circle-plus me-2"></i>Bank Policies</a></li>
    
    <li><a href="../auth/logout.php" class="text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a></li>
  </ul>
</nav>

<div class="main-content">
  <div class="dashboard-header">
    <h2>Welcome, Agent</h2>
    <div class="badge bg-success text-white px-3 py-2">AgriCycle Insurance Partner</div>
  </div>
  <div class="cards">
  <div class="card-box">
    <i class="fa-solid fa-clock"></i>
    <div class="card-title">Pending Requests</div>
    <div class="card-count"><?= $pendingRequests ?></div>
    <div class="badge-pulse">Pending</div>
  </div>

  <div class="card-box">
    <i class="fa-solid fa-building-columns"></i>
    <div class="card-title">Bank Policies</div>
    <div class="card-count"><?= $bankPolicies ?></div>
  </div>
</div>


    
  </div>
</div>

</body>
</html>