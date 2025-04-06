<?php 
session_start();
include '../config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];  
    $adminCodeInput = isset($_POST['admin_code']) ? $_POST['admin_code'] : '';

    // Optional Fields
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $location = $_POST['location'] ?? '';
    $company = $_POST['company'] ?? '';
    $agency = $_POST['agency'] ?? '';

    $table = '';
    if ($role === 'farmer') {
        $table = 'farmers';
        $insertQuery = "INSERT INTO farmers (name, email, password, phone, location) VALUES (?, ?, ?, ?, ?)";
    } elseif ($role === 'buyer') {
        $table = 'buyers';
        $insertQuery = "INSERT INTO buyers (name, email, password, phone, company) VALUES (?, ?, ?, ?, ?)";
    } elseif ($role === 'insurance_agent') {
        $table = 'insurance_agents';
        $insertQuery = "INSERT INTO insurance_agents (name, email, password, agency, phone) VALUES (?, ?, ?, ?, ?)";
    } elseif ($role === 'admin') {
        $adminCodeSecret = 'AGRICYCLE'; // Replace with your actual secure code
        if ($adminCodeInput !== $adminCodeSecret) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showNotification('Unauthorized admin registration attempt', 'error');
                    });
                  </script>";
        } else {
            $table = 'admins';
            $insertQuery = "INSERT INTO admins (email, password) VALUES (?, ?)";
        }
    } else {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification('Invalid role selected', 'error');
                });
              </script>";
    }

    if (!isset($error)) {
        $checkQuery = "SELECT * FROM $table WHERE email=?";
        $stmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $checkResult = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($checkResult) > 0) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showNotification('Email already exists for this role', 'error');
                    });
                  </script>";
        } else {
            $stmt = mysqli_prepare($conn, $insertQuery);

            if ($role === 'farmer') {
                mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $password, $phone, $location);
            } elseif ($role === 'buyer') {
                mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $password, $phone, $company);
            } elseif ($role === 'insurance_agent') {
                mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $password, $agency, $phone);
            } elseif ($role === 'admin') {
                mysqli_stmt_bind_param($stmt, "ss", $email, $password);
            }

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['user_id'] = mysqli_insert_id($conn);
                $_SESSION['role'] = $role;
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showNotification('Registration successful! Redirecting...', 'success');
                            setTimeout(() => {
                                window.location.href = 'login.php';
                            }, 2000);
                        });
                      </script>";
                exit();
            } else {
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showNotification('Database error', 'error');
                        });
                      </script>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | AgriCycle</title>
    <style>
        :root {
            --primary-green: #4CAF50;
            --dark-green: #388E3C;
            --light-green: #C8E6C9;
            --soil-brown: #8D6E63;
            --harvest-gold: #FFD54F;
            --error-red: #F44336;
            --success-green: #4CAF50;
            --text-dark: #333;
            --text-light: #f5f5f5;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(76, 175, 80, 0.1), rgba(76, 175, 80, 0.1)), 
                        url('../assets/images/farm-background.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .notification-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        
        .notification {
            position: relative;
            padding: 15px 25px;
            margin-bottom: 15px;
            border-radius: 8px;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            transform: translateX(120%);
            animation: slideIn 0.5s forwards, fadeOut 0.5s forwards 2s;
            overflow: hidden;
        }
        
        .notification.success {
            background-color: var(--success-green);
        }
        
        .notification.error {
            background-color: var(--error-red);
        }
        
        .notification-icon {
            margin-right: 15px;
            font-size: 24px;
        }
        
        @keyframes slideIn {
            to {
                transform: translateX(0);
            }
        }
        
        @keyframes fadeOut {
            to {
                opacity: 0;
            }
        }
        
        .register-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 500px;
            padding: 40px;
            margin: 20px;
            transform: translateY(20px);
            opacity: 0;
            animation: fadeInUp 0.6s forwards 0.2s;
            border: 1px solid rgba(76, 175, 80, 0.2);
        }
        
        @keyframes fadeInUp {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-title {
            color: var(--primary-green);
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .form-subtitle {
            color: var(--soil-brown);
            font-size: 14px;
        }
        
        .form-icon {
            font-size: 50px;
            color: var(--primary-green);
            margin-bottom: 15px;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-15px);
            }
            60% {
                transform: translateY(-7px);
            }
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-dark);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }
        
        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
            outline: none;
        }
        
        .btn {
            width: 100%;
            padding: 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: var(--primary-green);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--dark-green);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }
        
        .btn-secondary {
            background: white;
            color: var(--text-dark);
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .btn-secondary:hover {
            background: #f5f5f5;
            transform: translateY(-2px);
        }
        
        .form-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: var(--soil-brown);
        }
        
        .form-footer a {
            color: var(--primary-green);
            font-weight: 600;
            text-decoration: none;
        }
        
        .form-footer a:hover {
            text-decoration: underline;
        }
        
        .role-icon {
            margin-right: 8px;
            color: var(--primary-green);
        }
        
        #admin-code-field, #extra-fields > div { 
            display: none;
            animation: fadeIn 0.3s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .location-loading {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(76, 175, 80, 0.3);
            border-radius: 50%;
            border-top-color: var(--primary-green);
            animation: spin 1s ease-in-out infinite;
            margin-left: 8px;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        @media (max-width: 576px) {
            .register-container {
                padding: 30px 20px;
                margin: 15px;
            }
            
            .form-title {
                font-size: 24px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="notification-container" id="notificationContainer"></div>
    
    <div class="register-container">
        <div class="form-header">
            <div class="form-icon">
                <i class="fas fa-seedling"></i>
            </div>
            <h1 class="form-title">Join AgriCycle</h1>
            <p class="form-subtitle">Connect with the agricultural community</p>
        </div>
        
        <form method="POST" id="registrationForm">
            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="your@email.com" required>
                <i class="fas fa-envelope form-control-icon"></i>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Create a password" required>
                <i class="fas fa-lock form-control-icon"></i>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="role">I am a</label>
                <select id="role" name="role" class="form-control" required>
                    <option value="farmer"><i class="fas fa-tractor role-icon"></i> Farmer</option>
                    <option value="buyer"><i class="fas fa-shopping-basket role-icon"></i> Buyer</option>
                    <option value="insurance_agent"><i class="fas fa-shield-alt role-icon"></i> Insurance Agent</option>
                    <option value="admin"><i class="fas fa-user-shield role-icon"></i> Administrator</option>
                </select>
            </div>
            
            <div class="form-group" id="admin-code-field">
                <label class="form-label" for="admin_code">Admin Access Code</label>
                <input type="password" id="admin_code" name="admin_code" class="form-control" placeholder="Enter admin code">
                <i class="fas fa-key form-control-icon"></i>
            </div>
            
            <div id="extra-fields">
                <div id="common-fields">
                    <div class="form-group">
                        <label class="form-label" for="name">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Your full name">
                        <i class="fas fa-user form-control-icon"></i>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="form-control" placeholder="+1 (123) 456-7890">
                        <i class="fas fa-phone form-control-icon"></i>
                    </div>
                </div>
                
                <div class="form-group" id="farmer-location">
                    <label class="form-label" for="location">
                        Farm Location 
                        <span id="location-loading" class="location-loading"></span>
                    </label>
                    <input type="text" id="location" name="location" class="form-control" placeholder="Detecting your location..." readonly>
                    <i class="fas fa-map-marker-alt form-control-icon"></i>
                    <small class="text-muted">We're detecting your location to connect you with local buyers</small>
                </div>
                
                <div class="form-group" id="buyer-company">
                    <label class="form-label" for="company">Company Name</label>
                    <input type="text" id="company" name="company" class="form-control" placeholder="Your company name">
                    <i class="fas fa-building form-control-icon"></i>
                </div>
                
                <div class="form-group" id="agent-agency">
                    <label class="form-label" for="agency">Insurance Agency</label>
                    <input type="text" id="agency" name="agency" class="form-control" placeholder="Your agency name">
                    <i class="fas fa-shield-alt form-control-icon"></i>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary mt-4">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
            
            <a href="../index.php" class="btn btn-secondary mt-2">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
            
            <div class="form-footer">
                Already have an account? <a href="login.php">Sign in here</a>
            </div>
        </form>
    </div>
    
    <script>
        function showNotification(message, type) {
            const container = document.getElementById('notificationContainer');
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            
            const icon = document.createElement('span');
            icon.className = 'notification-icon';
            
            switch(type) {
                case 'success':
                    icon.innerHTML = '<i class="fas fa-check-circle"></i>';
                    break;
                case 'error':
                    icon.innerHTML = '<i class="fas fa-exclamation-circle"></i>';
                    break;
            }
            
            const text = document.createElement('span');
            text.textContent = message;
            
            notification.appendChild(icon);
            notification.appendChild(text);
            container.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 2500);
        }
        
        const roleSelect = document.getElementById('role');
        const adminCodeField = document.getElementById('admin-code-field');
        const extraFields = document.getElementById('extra-fields');
        const commonFields = document.getElementById('common-fields');
        const farmerLoc = document.getElementById('farmer-location');
        const buyerComp = document.getElementById('buyer-company');
        const agentAgency = document.getElementById('agent-agency');
        const locationLoading = document.getElementById('location-loading');

        function updateFields() {
            const role = roleSelect.value;

            adminCodeField.style.display = (role === 'admin') ? 'block' : 'none';
            extraFields.style.display = (role === 'admin') ? 'none' : 'block';
            commonFields.style.display = (role !== 'admin') ? 'block' : 'none';

            farmerLoc.style.display = (role === 'farmer') ? 'block' : 'none';
            buyerComp.style.display = (role === 'buyer') ? 'block' : 'none';
            agentAgency.style.display = (role === 'insurance_agent') ? 'block' : 'none';
        }

        roleSelect.addEventListener('change', updateFields);
        updateFields();
        
        // Enhanced location detection
        document.addEventListener('DOMContentLoaded', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    async function(position) {
                        try {
                            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${position.coords.latitude}&lon=${position.coords.longitude}`);
                            const data = await response.json();
                            document.getElementById('location').value = data.display_name || "Location detected";
                            locationLoading.style.display = 'none';
                        } catch (error) {
                            document.getElementById('location').value = "Couldn't get address (coordinates available)";
                            locationLoading.style.display = 'none';
                        }
                    },
                    function(error) {
                        document.getElementById('location').value = "Location access denied";
                        locationLoading.style.display = 'none';
                        document.getElementById('location').readOnly = false;
                    }
                );
            } else {
                document.getElementById('location').value = "Geolocation not supported";
                locationLoading.style.display = 'none';
                document.getElementById('location').readOnly = false;
            }
        });
    </script>
</body>
</html>