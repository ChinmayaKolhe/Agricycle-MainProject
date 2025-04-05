<?php
session_start();
include '../config/db_connect.php';

function checkCredentials($conn, $table, $email, $password) {
    $query = "SELECT * FROM $table WHERE email=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check Admin
    $admin = checkCredentials($conn, "admins", $email, $password);
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['username'] = $admin['name'];
        $_SESSION['role'] = 'admin';
        header("Location: ../dashboard/admin_dashboard.php");
        exit();
    }

    // Check Farmer
    $farmer = checkCredentials($conn, "farmers", $email, $password);
    if ($farmer && password_verify($password, $farmer['password'])) {
        if ($farmer['is_verified'] == 1) {
            $_SESSION['user_id'] = $farmer['id'];
            $_SESSION['username'] = $farmer['name'];
            $_SESSION['role'] = 'farmer';
            header("Location: ../dashboard/farmer_dashboard.php");
            exit();
        } else {
            $_SESSION['user_id'] = $farmer['id'];
            $_SESSION['role'] = 'farmer_pending';
            header("Location: verify_aadhaar.php");
            exit();
        }
    }

    // Check Buyer
    $buyer = checkCredentials($conn, "buyers", $email, $password);
    if ($buyer && password_verify($password, $buyer['password'])) {
        $_SESSION['user_id'] = $buyer['id'];
        $_SESSION['username'] = $buyer['name'];
        $_SESSION['role'] = 'buyer';
        header("Location: ../dashboard/buyer_dashboard.php");
        exit();
    }

    // Check Insurance Agent
    $agent = checkCredentials($conn, "insurance_agents", $email, $password);
    if ($agent && password_verify($password, $agent['password'])) {
        $_SESSION['user_id'] = $agent['id'];
        $_SESSION['username'] = $agent['name'];
        $_SESSION['role'] = 'insurance_agent';
        header("Location: ../dashboard/insurance_agent_dashboard.php");
        exit();
    }

    $error = "Invalid Email or Password!";
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | AgriCycle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <style>
        body {
            background: url('../assets/images/login.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            width: 350px;
            padding: 20px;
            background: transparent;
            text-align: center;
        }
        .login-form {
            background: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 8px;
            color: white;
        }
        .form-control {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        .btn-home {
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <form class="login-form" method="POST">
        <h3>Login</h3>

        <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>

        <div class="form-group mb-2">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>

        <div class="form-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>

        <button type="submit" class="btn btn-success w-100">Login</button>

        <p class="mt-2">Don't have an account? <a href="register.php">Register</a></p>
        <a href="../index.php" class="btn btn-primary btn-home">Go to Home</a>
    </form>
</div>

</body>
</html>
