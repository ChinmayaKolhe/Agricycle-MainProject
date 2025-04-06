<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'buyer_pending' && $_SESSION['role'] !== 'buyer')) {
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('Please login as buyer to continue', 'error');
                setTimeout(() => {
                    window.location.href = '../auth/login.php';
                }, 2500);
            });
          </script>";
    exit();
}

include '../config/db_connect.php';

// Redirect verified buyers away from Aadhaar page
$user_id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT is_verified FROM buyers WHERE id = $user_id");
$row = mysqli_fetch_assoc($result);

if ($row['is_verified'] == 1) {
    $_SESSION['role'] = 'buyer';
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('You are already verified! Redirecting...', 'success');
                setTimeout(() => {
                    window.location.href = '../dashboard/buyer_dashboard.php';
                }, 2500);
            });
          </script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['aadhaar'])) {
    $target_dir = "../uploads/aadhaar_buyers/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $filename = "buyer_" . $user_id . "_" . basename($_FILES["aadhaar"]["name"]);
    $target_file = $target_dir . $filename;

    if (move_uploaded_file($_FILES["aadhaar"]["tmp_name"], $target_file)) {
        $db_path = "uploads/aadhaar_buyers/" . $filename;
        mysqli_query($conn, "UPDATE buyers SET aadhaar_path = '$db_path', verification_requested = 1 WHERE id = $user_id");
        $message = "<script>
                      document.addEventListener('DOMContentLoaded', function() {
                          showNotification('Aadhaar uploaded successfully! Please wait for admin approval.', 'success');
                      });
                    </script>";
    } else {
        $message = "<script>
                      document.addEventListener('DOMContentLoaded', function() {
                          showNotification('Error uploading Aadhaar. Please try again.', 'error');
                      });
                    </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Aadhaar Verification | AgriCycle</title>
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
        
        .verification-container {
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
        
        .verification-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .verification-title {
            color: var(--primary-green);
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .verification-subtitle {
            color: var(--soil-brown);
            font-size: 14px;
        }
        
        .verification-icon {
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
        
        .file-input-container {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }
        
        .file-input-button {
            border: 2px dashed var(--primary-green);
            border-radius: 8px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: rgba(76, 175, 80, 0.05);
        }
        
        .file-input-button:hover {
            background: rgba(76, 175, 80, 0.1);
            border-color: var(--dark-green);
        }
        
        .file-input-button i {
            font-size: 40px;
            color: var(--primary-green);
            margin-bottom: 15px;
            display: block;
        }
        
        .file-input-button span {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .file-input-button small {
            color: var(--soil-brown);
            font-size: 12px;
        }
        
        .file-input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .file-name {
            margin-top: 10px;
            font-size: 14px;
            color: var(--primary-green);
            font-weight: 500;
            display: none;
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
        
        .info-box {
            background: rgba(76, 175, 80, 0.1);
            border-left: 4px solid var(--primary-green);
            padding: 15px;
            border-radius: 0 8px 8px 0;
            margin-bottom: 25px;
        }
        
        .info-box p {
            margin: 0;
            font-size: 14px;
            color: var(--soil-brown);
        }
        
        @media (max-width: 576px) {
            .verification-container {
                padding: 30px 20px;
                margin: 15px;
            }
            
            .verification-title {
                font-size: 24px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="notification-container" id="notificationContainer"></div>
    
    <div class="verification-container">
        <div class="verification-header">
            <div class="verification-icon">
                <i class="fas fa-id-card"></i>
            </div>
            <h1 class="verification-title">Aadhaar Verification</h1>
            <p class="verification-subtitle">Complete your buyer profile verification</p>
        </div>
        
        <div class="info-box">
            <p><i class="fas fa-info-circle"></i> Your Aadhaar details will be used solely for verification purposes and will be kept secure.</p>
        </div>
        
        <form method="post" enctype="multipart/form-data" id="verificationForm">
            <div class="form-group">
                <label class="form-label">Upload Aadhaar Card</label>
                <div class="file-input-container">
                    <div class="file-input-button" id="fileInputButton">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>Click to upload Aadhaar</span>
                        <small>PDF, JPG, or PNG (Max 5MB)</small>
                        <div class="file-name" id="fileName"></div>
                    </div>
                    <input type="file" name="aadhaar" id="aadhaar" class="file-input" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary" id="submitButton">
                <i class="fas fa-check-circle"></i> Submit for Verification
            </button>
        </form>
    </div>
    
    <?= $message ?>
    
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
        
        // File input handling
        const fileInput = document.getElementById('aadhaar');
        const fileInputButton = document.getElementById('fileInputButton');
        const fileNameDisplay = document.getElementById('fileName');
        
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                if (file.size > 5 * 1024 * 1024) { // 5MB limit
                    showNotification('File size exceeds 5MB limit', 'error');
                    this.value = '';
                    return;
                }
                
                fileNameDisplay.textContent = file.name;
                fileNameDisplay.style.display = 'block';
                fileInputButton.querySelector('span').textContent = 'File selected';
                fileInputButton.querySelector('small').textContent = 'Click to change';
                fileInputButton.style.borderColor = 'var(--primary-green)';
                fileInputButton.style.backgroundColor = 'rgba(76, 175, 80, 0.1)';
            }
        });
        
        // Form submission handling
        document.getElementById('verificationForm').addEventListener('submit', function() {
            const submitButton = document.getElementById('submitButton');
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
            submitButton.disabled = true;
        });
    </script>
</body>
</html>