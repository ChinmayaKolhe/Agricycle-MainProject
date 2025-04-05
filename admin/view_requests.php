<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/db_connect.php';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Loan/Insurance Requests | AgriCycle</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f9fbe7;
    }

    .request-table {
      margin-top: 20px;
    }

    .request-table table {
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    thead {
      background-color: #33691e;
      color: white;
    }
  </style>
</head>
<body>
<div class="container mt-5">
  <h2 class="mb-4">Loan/Insurance Requests</h2>
  <div class="request-table">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>Request Type</th>
          <th>User Email</th>
          <th>Description</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM requests");
        $count = 1;
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>
                  <td>{$count}</td>
                  <td>{$row['type']}</td>
                  <td>{$row['email']}</td>
                  <td>{$row['description']}</td>
                  <td>{$row['status']}</td>
                </tr>";
          $count++;
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
