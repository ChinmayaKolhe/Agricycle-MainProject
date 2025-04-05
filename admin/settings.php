<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Settings | AgriCycle</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to bottom, #e8f5e9, #fffde7);
    }

    .settings-box {
      max-width: 600px;
      margin: 80px auto;
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }

    .settings-box h4 {
      margin-bottom: 20px;
      color: #2e7d32;
    }
  </style>
</head>
<body>
<div class="settings-box">
  <h4>Account Settings</h4>
  <form>
    <div class="mb-3">
      <label>Email</label>
      <input type="email" class="form-control" value="admin@agricycle.com" readonly>
    </div>

    <div class="mb-3">
      <label>Change Password</label>
      <input type="password" class="form-control" placeholder="New Password">
    </div>

    <div class="mb-3">
      <label>Confirm Password</label>
      <input type="password" class="form-control" placeholder="Confirm New Password">
    </div>

    <button class="btn btn-success w-100">Update Settings</button>
  </form>
</div>
</body>
</html>
