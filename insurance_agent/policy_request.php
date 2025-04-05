<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'insurance_agent') {
    header("Location: ../auth/login.php");
    exit();
}

include '../config/db_connect.php';

$agent_id = $_SESSION['user_id'];

// Join with necessary tables
$sql = "SELECT pr.id, pr.status, pr.created_at AS requested_at,
               f.name AS farmer_name, f.crop_type,
               bp.name AS policy_name
        FROM policy_requests pr
        JOIN farmers f ON pr.farmer_id = f.id
        JOIN bank_policies bp ON pr.policy_id = bp.id
        WHERE pr.agent_id = ?
        ORDER BY pr.created_at DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $agent_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<div class="main-content">
  <h2 class="mb-4">Policy Requests</h2>
  <table class="table table-bordered table-hover bg-white">
    <thead>
      <tr>
        <th>Farmer Name</th>
        <th>Crop Type</th>
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
          <td><?= htmlspecialchars($row['crop_type']) ?></td>
          <td><?= htmlspecialchars($row['policy_name']) ?></td>
          <td><?= date('d M Y, H:i A', strtotime($row['requested_at'])) ?></td>
          <td>
            <span class="status-badge <?= strtolower($row['status']) ?>">
              <?= ucfirst($row['status']) ?>
            </span>
          </td>
          <td>
            <?php if ($row['status'] == 'Pending') { ?>
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
