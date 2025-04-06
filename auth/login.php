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
        if ($buyer['is_verified'] == 1) {
            $_SESSION['user_id'] = $buyer['id'];
            $_SESSION['username'] = $buyer['name'];
            $_SESSION['role'] = 'buyer';
            header("Location: ../dashboard/buyer_dashboard.php");
            exit();
        } else {
            $_SESSION['user_id'] = $buyer['id'];
            $_SESSION['role'] = 'buyer_pending';
            header("Location: verify_aadhaar_buyer.php");
            exit();
        }
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        :root {
            --primary-green: #2e7d32;
            --light-green: #81c784;
            --dark-green: #1b5e20;
            --earth-brown: #5d4037;
            --sun-yellow: #ffd54f;
            --harvest-orange: #fb8c00;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                        url('../assets/images/farm-field.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
            animation: fadeIn 0.5s ease-out;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }
        
        .login-header {
            background: linear-gradient(90deg, var(--primary-green), var(--dark-green));
            color: white;
            padding: 25px;
            text-align: center;
            position: relative;
        }
        
        .login-header::before {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 15px solid transparent;
            border-right: 15px solid transparent;
            border-top: 15px solid var(--dark-green);
        }
        
        .login-header h3 {
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .login-header p {
            opacity: 0.9;
            font-size: 0.9rem;
        }
        
        .login-body {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--earth-brown);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            background-color: #f9f9f9;
        }
        
        .form-control:focus {
            border-color: var(--light-green);
            box-shadow: 0 0 0 3px rgba(129, 199, 132, 0.2);
            background-color: white;
        }
        
        .input-icon {
            position: absolute;
            right: 15px;
            top: 40px;
            color: #aaa;
        }
        
        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-green);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        .btn-login:hover {
            background-color: var(--dark-green);
            transform: translateY(-2px);
        }
        
        .login-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        .login-footer a {
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .login-footer a:hover {
            color: var(--dark-green);
            text-decoration: underline;
        }
        
        .btn-home {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 15px;
            background-color: white;
            color: var(--primary-green);
            border: 1px solid var(--primary-green);
            border-radius: 8px;
            font-weight: 500;
            margin-top: 15px;
            transition: all 0.3s;
        }
        
        .btn-home:hover {
            background-color: var(--primary-green);
            color: white;
            transform: translateY(-2px);
        }
        
        .alert-danger {
            background-color: #ffebee;
            color: #c62828;
            border-left: 4px solid #c62828;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .role-selector {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .role-btn {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 20px;
            background-color: white;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.8rem;
        }
        
        .role-btn.active {
            background-color: var(--primary-green);
            color: white;
            border-color: var(--primary-green);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 576px) {
            .login-container {
                max-width: 100%;
            }
            
            .login-body {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container animate__animated animate__fadeIn">
        <div class="login-card">
            <div class="login-header">
                <h3><i class="bi bi-tree"></i> AgriCycle</h3>
                <p>Login to your account</p>
            </div>
            
            <div class="login-body">
                <?php if (isset($error)): ?>
                    <div class="alert-danger animate__animated animate__shakeX">
                        <i class="bi bi-exclamation-triangle-fill"></i> <?= $error ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                        <i class="bi bi-envelope input-icon"></i>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                        <i class="bi bi-lock input-icon"></i>
                    </div>
                    
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                        <a href="forgot_password.php" style="float: right;">Forgot password?</a>
                    </div>
                    
                    <button type="submit" class="btn-login">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </button>
                </form>
                
                <div class="login-footer">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                    <a href="../index.php" class="btn-home">
                        <i class="bi bi-house-door"></i> Go to Home
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple animation for form elements
        document.addEventListener('DOMContentLoaded', function() {
            const formGroups = document.querySelectorAll('.form-group');
            formGroups.forEach((group, index) => {
                group.style.opacity = '0';
                group.style.transform = 'translateY(20px)';
                group.style.transition = `all 0.5s ease ${index * 0.1}s`;
                
                setTimeout(() => {
                    group.style.opacity = '1';
                    group.style.transform = 'translateY(0)';
                }, 100);
            });
            
            // Focus on email field when page loads
            document.getElementById('email').focus();
        });
    </script>
</body>
</html>