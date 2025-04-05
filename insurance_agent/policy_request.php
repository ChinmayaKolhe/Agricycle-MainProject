<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'insurance_agent') {
    header("Location: ../auth/login.php");
    exit();
}

include '../config/db_connect.php';

$agent_id = $_SESSION['user_id'];

// Fetch policy requests assigned to the current agent
$sql = "SELECT pr.id, pr.status, pr.applied_at AS requested_at,
               f.name AS farmer_name,
               bp.name AS policy_name
        FROM policy_requests pr
        JOIN farmers f ON pr.farmer_id = f.id
        JOIN bank_policies bp ON pr.policy_id = bp.id
        WHERE pr.agent_id = ?
        ORDER BY pr.applied_at DESC";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "i", $agent_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Policy Requests | AgriCycle</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #f2fcf5, #d6f5f2);
      padding: 30px;
    }
    .main-content {
      background-color: #ffffff;
      border-radius: 16px;
      padding: 30px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
      animation: fadeIn 0.5s ease-in;
    }
    h2 {
      color: #00796b;
      font-weight: 600;
    }
    .status-badge {
      padding: 5px 10px;
      border-radius: 10px;
      font-size: 0.9em;
      font-weight: 500;
      color: white;
    }
    .status-badge.pending {
      background-color: #f0ad4e;
    }
    .status-badge.approved {
      background-color: #5cb85c;
    }
    .status-badge.rejected {
      background-color: #d9534f;
    }
    .btn-sm {
      margin-right: 5px;
      transition: all 0.2s ease-in-out;
    }
    .btn-sm:hover {
      transform: scale(1.05);
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

<div class="main-content">
  <h2 class="mb-4">Policy Requests</h2>

  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-info">
      <?= $_SESSION['message']; unset($_SESSION['message']); ?>
    </div>
  <?php endif; ?>

  <table class="table table-bordered table-hover bg-white">
    <thead class="table-success">
      <tr>
        <th>Farmer Name</th>
        <th>Policy Name</th>
        <th>Requested Date</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
          <td><?= htmlspecialchars($row['farmer_name']) ?></td>
          <td><?= htmlspecialchars($row['policy_name']) ?></td>
          <td><?= date('d M Y, H:i A', strtotime($row['requested_at'])) ?></td>
          <td>
            <span class="status-badge <?= strtolower($row['status']) ?>">
              <?= ucfirst($row['status']) ?>
            </span>
          </td>
          <td>
            <?php if ($row['status'] === 'Pending') { ?>
              <a href="update_policy_status.php?id=<?= $row['id'] ?>&status=Approved" class="btn btn-sm btn-success">Approve</a>
              <a href="update_policy_status.php?id=<?= $row['id'] ?>&status=Rejected" class="btn btn-sm btn-danger">Reject</a>
            <?php } else { ?>
              <span class="text-muted">No actions</span>
            <?php } ?>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

</body>
</html>
