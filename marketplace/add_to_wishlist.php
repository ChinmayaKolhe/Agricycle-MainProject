<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('You must log in to add items to the wishlist', 'warning');
                setTimeout(() => {
                    window.location.href='../login.php';
                }, 2500);
            });
          </script>";
    exit;
}

if (isset($_GET['id'])) {
    $user_id = $_SESSION['user_id'];
    $item_id = $_GET['id'];

    // Ensure the item exists in marketplace_items
    $check_product = "SELECT id FROM marketplace_items WHERE id = ?";
    $stmt = $conn->prepare($check_product);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification('Error: Product does not exist', 'error');
                    setTimeout(() => {
                        window.location.href='index.php';
                    }, 2500);
                });
              </script>";
        exit;
    }

    // Check if already in wishlist
    $check_query = "SELECT * FROM wishlist WHERE user_id = ? AND item_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $user_id, $item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Insert into wishlist
        $insert_query = "INSERT INTO wishlist (user_id, item_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ii", $user_id, $item_id);

        if ($stmt->execute()) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showNotification('Product added to wishlist successfully!', 'success');
                        setTimeout(() => {
                            window.location.href='index.php';
                        }, 2500);
                    });
                  </script>";
        } else {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showNotification('Error adding product to wishlist', 'error');
                        setTimeout(() => {
                            window.location.href='index.php';
                        }, 2500);
                    });
                  </script>";
        }
    } else {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification('Product is already in your wishlist!', 'info');
                    setTimeout(() => {
                        window.location.href='index.php';
                    }, 2500);
                });
              </script>";
    }
} else {
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('Invalid request! Please try again', 'error');
                setTimeout(() => {
                    window.location.href='index.php';
                }, 2500);
            });
          </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist Update</title>
    <style>
        :root {
            --primary-green: #4CAF50;
            --dark-green: #388E3C;
            --light-green: #C8E6C9;
            --soil-brown: #8D6E63;
            --harvest-gold: #FFD54F;
            --error-red: #F44336;
            --warning-orange: #FF9800;
            --sky-blue: #81D4FA;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: linear-gradient(to bottom, rgba(201, 255, 203, 0.3), rgba(255, 255, 255, 0.8)), 
                              url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><path fill="%234CAF50" fill-opacity="0.1" d="M30,10 Q50,0 70,10 Q90,20 80,40 Q70,60 50,70 Q30,60 20,40 Q10,20 30,10 Z"/></svg>');
            background-size: 200px;
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
        
        .notification::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: rgba(255, 255, 255, 0.5);
            animation: progressBar 2.5s linear forwards;
        }
        
        .notification.success {
            background-color: var(--primary-green);
        }
        
        .notification.error {
            background-color: var(--error-red);
        }
        
        .notification.warning {
            background-color: var(--warning-orange);
        }
        
        .notification.info {
            background-color: var(--sky-blue);
            color: #333;
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
        
        @keyframes progressBar {
            from {
                width: 100%;
            }
            to {
                width: 0%;
            }
        }
        
        .loading-container {
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 90%;
        }
        
        .loading-title {
            color: var(--dark-green);
            margin-bottom: 20px;
            font-size: 22px;
        }
        
        .loading-animation {
            display: inline-block;
            position: relative;
            width: 80px;
            height: 80px;
        }
        
        .loading-animation div {
            position: absolute;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--primary-green);
            animation: loading 1.2s linear infinite;
        }
        
        .loading-animation div:nth-child(1) {
            top: 8px;
            left: 8px;
            animation-delay: 0s;
        }
        
        .loading-animation div:nth-child(2) {
            top: 8px;
            left: 32px;
            animation-delay: -0.4s;
        }
        
        .loading-animation div:nth-child(3) {
            top: 8px;
            left: 56px;
            animation-delay: -0.8s;
        }
        
        .loading-animation div:nth-child(4) {
            top: 32px;
            left: 8px;
            animation-delay: -0.4s;
        }
        
        .loading-animation div:nth-child(5) {
            top: 32px;
            left: 32px;
            animation-delay: -0.8s;
        }
        
        .loading-animation div:nth-child(6) {
            top: 32px;
            left: 56px;
            animation-delay: -1.2s;
        }
        
        .loading-animation div:nth-child(7) {
            top: 56px;
            left: 8px;
            animation-delay: -0.8s;
        }
        
        .loading-animation div:nth-child(8) {
            top: 56px;
            left: 32px;
            animation-delay: -1.2s;
        }
        
        .loading-animation div:nth-child(9) {
            top: 56px;
            left: 56px;
            animation-delay: -1.6s;
        }
        
        @keyframes loading {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(0.5);
                opacity: 0.5;
            }
        }
    </style>
</head>
<body>
    <div class="notification-container" id="notificationContainer"></div>
    
    <div class="loading-container">
        <h2 class="loading-title">Updating Your Wishlist</h2>
        <div class="loading-animation">
            <div></div><div></div><div></div>
            <div></div><div></div><div></div>
            <div></div><div></div><div></div>
        </div>
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
                    icon.innerHTML = '✓';
                    break;
                case 'error':
                    icon.innerHTML = '✗';
                    break;
                case 'warning':
                    icon.innerHTML = '⚠';
                    break;
                case 'info':
                    icon.innerHTML = 'ℹ';
                    break;
            }
            
            const text = document.createElement('span');
            text.textContent = message;
            
            notification.appendChild(icon);
            notification.appendChild(text);
            container.appendChild(notification);
            
            // Remove notification after animation completes
            setTimeout(() => {
                notification.remove();
            }, 2500);
        }
    </script>
</body>
</html>