<?php  
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'insurance_agent') {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/db_connect.php';

$agent_id = $_SESSION['user_id'];
$sql = "SELECT * FROM bank_policies WHERE agent_id = '$agent_id'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Active Policies | AgriCycle</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    body {
      background: #f0fdf4;
      font-family: 'Segoe UI', sans-serif;
      transition: background 0.3s ease;
    }
    nav {
      width: 250px;
      background: linear-gradient(to bottom, #065f46, #064e3b);
      color: white;
      height: 100vh;
      position: fixed;
      top: 0; left: 0;
      padding: 20px;
      transition: all 0.3s ease-in-out;
    }
    nav a {
      color: white;
      text-decoration: none;
      transition: color 0.3s ease;
    }
    nav a:hover {
      color: #d1fae5;
    }
    .main {
      margin-left: 270px;
      padding: 40px;
      animation: fadeIn 0.6s ease;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .table thead {
      background-color: #0f766e;
      color: white;
    }
    .btn {
      transition: all 0.3s ease-in-out;
    }
    .btn:hover {
      transform: scale(1.05);
    }
    .modal-content {
      border-radius: 1rem;
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
  <h3 class="mb-4">Active Policies</h3>

  <table class="table table-hover shadow">
    <thead>
      <tr>
        <th>Policy ID</th>
        <th>Title</th>
        <th>PDF</th>
        <th>Bank Portal</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
          <td>#<?php echo $row['id']; ?></td>
          <td><?php echo htmlspecialchars($row['name']); ?></td>
          <td>
            <?php if (!empty($row['pdf_path'])) { ?>
              <a href="../<?php echo $row['pdf_path']; ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                <i class="fa fa-download"></i> PDF
              </a>
            <?php } else { echo "-"; } ?>
          </td>
          <td>
          <?php 
            if (!empty($row['bank_link']) && filter_var($row['bank_link'], FILTER_VALIDATE_URL)) { 
              $bank_link = htmlspecialchars($row['bank_link']);
              if (!preg_match("~^(?:f|ht)tps?://~i", $bank_link)) {
                  $bank_link = "https://" . $bank_link;
              }
          ?>
            <a href="<?php echo $bank_link; ?>" class="btn btn-sm btn-success" target="_blank">
              <i class="fa fa-external-link-alt"></i> Visit
            </a>
          <?php } else { echo "-"; } ?>
        </td>

          <td>
            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">
              <i class="fa fa-edit"></i> Edit
            </button>
            <a href="delete_policy.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure to delete this policy?');" class="btn btn-danger btn-sm">
              <i class="fa fa-trash"></i> Delete
            </a>
          </td>
        </tr>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editLabel<?php echo $row['id']; ?>" aria-hidden="true">
          <div class="modal-dialog">
            <form action="edit_policy.php" method="POST">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="editLabel<?php echo $row['id']; ?>">Edit Policy</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="policy_id" value="<?php echo $row['id']; ?>">
                  <div class="mb-3">
                    <label for="name" class="form-label">Policy Title</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="bank_link" class="form-label">Bank Portal Link</label>
                    <input type="url" name="bank_link" class="form-control" value="<?php echo htmlspecialchars($row['bank_link']); ?>" required>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
              </div>
            </form>
          </div>
        </div>

      <?php } ?>
    </tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
