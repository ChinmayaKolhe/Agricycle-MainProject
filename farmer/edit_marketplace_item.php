<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('Unauthorized access. Please login', 'error');
                setTimeout(() => {
                    window.location.href = '../auth/login.php';
                }, 2500);
            });
          </script>";
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = mysqli_query($conn, "SELECT * FROM marketplace_items WHERE id = '$id'");
    $item = mysqli_fetch_assoc($query);
    
    if (!$item) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification('Item not found', 'error');
                    setTimeout(() => {
                        window.location.href = 'marketplace.php';
                    }, 2500);
                });
              </script>";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $contact_info = mysqli_real_escape_string($conn, $_POST['contact_info']);

    $sql = "UPDATE marketplace_items SET 
            item_name='$item_name', description='$description', price='$price', 
            quantity='$quantity', contact_info='$contact_info' WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification('Listing updated successfully! Redirecting...', 'success');
                    setTimeout(() => {
                        window.location.href = 'marketplace.php';
                    }, 2000);
                });
              </script>";
        exit();
    } else {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification('Error updating listing: " . addslashes(mysqli_error($conn)) . "', 'error');
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
    <title>Edit Listing | AgriCycle Marketplace</title>
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
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            color: var(--text-dark);
            background-image: linear-gradient(to bottom, rgba(201, 255, 203, 0.3), rgba(255, 255, 255, 0.8)), 
                              url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><path fill="%234CAF50" fill-opacity="0.1" d="M30,10 Q50,0 70,10 Q90,20 80,40 Q70,60 50,70 Q30,60 20,40 Q10,20 30,10 Z"/></svg>');
            background-size: 200px;
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
        
        .edit-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 500px;
            padding: 30px;
            margin: 20px;
            transform: translateY(20px);
            opacity: 0;
            animation: fadeInUp 0.6s forwards 0.2s;
            border-left: 5px solid var(--primary-green);
        }
        
        @keyframes fadeInUp {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .edit-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .edit-title {
            color: var(--primary-green);
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .edit-subtitle {
            color: var(--soil-brown);
            font-size: 14px;
        }
        
        .edit-icon {
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
        }
        
        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
            outline: none;
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }
        
        .btn {
            flex: 1;
            padding: 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: var(--primary-green);
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            background: var(--dark-green);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }
        
        .btn-outline {
            background: transparent;
            color: var(--primary-green);
            border: 2px solid var(--primary-green);
        }
        
        .btn-outline:hover {
            background: rgba(76, 175, 80, 0.1);
            transform: translateY(-2px);
        }
        
        .price-input {
            position: relative;
        }
        
        .price-input::before {
            content: '₹';
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-weight: 500;
            color: var(--soil-brown);
            z-index: 1;
        }
        
        .price-input input {
            padding-left: 30px;
        }
        
        @media (max-width: 576px) {
            .edit-container {
                padding: 25px 20px;
                margin: 15px;
            }
            
            .edit-title {
                font-size: 24px;
            }
            
            .button-group {
                flex-direction: column;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="notification-container" id="notificationContainer"></div>
    
    <div class="edit-container">
        <div class="edit-header">
            <div class="edit-icon">
                <i class="fas fa-edit"></i>
            </div>
            <h1 class="edit-title">Edit Your Listing</h1>
            <p class="edit-subtitle">Update your agricultural waste item details</p>
        </div>
        
        <form method="POST" id="editForm">
            <div class="form-group">
                <label class="form-label" for="item_name">Item Name</label>
                <input type="text" id="item_name" name="item_name" class="form-control" 
                       value="<?= htmlspecialchars($item['item_name']) ?>" 
                       placeholder="e.g., Rice Husk, Banana Stems" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="description">Description</label>
                <textarea id="description" name="description" class="form-control" 
                          placeholder="Describe the item, condition, possible uses" required><?= htmlspecialchars($item['description']) ?></textarea>
            </div>
            
            <div class="form-group price-input">
                <label class="form-label" for="price">Price per kg (₹)</label>
                <input type="number" id="price" name="price" class="form-control" 
                       value="<?= htmlspecialchars($item['price']) ?>" 
                       min="0" step="0.01" placeholder="Enter price" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="quantity">Available Quantity (kg)</label>
                <input type="number" id="quantity" name="quantity" class="form-control" 
                       value="<?= htmlspecialchars($item['quantity']) ?>" 
                       min="1" placeholder="Enter quantity" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="contact_info">Contact Information</label>
                <input type="text" id="contact_info" name="contact_info" class="form-control" 
                       value="<?= htmlspecialchars($item['contact_info']) ?>" 
                       placeholder="Phone number or email" required>
            </div>
            
            <div class="button-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Listing
                </button>
                <button type="button" class="btn btn-outline" onclick="window.location.href='marketplace.php'">
                    <i class="fas fa-times"></i> Cancel
                </button>
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
        
        // Form submission handling
        document.getElementById('editForm').addEventListener('submit', function() {
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
            submitButton.disabled = true;
        });
    </script>
</body>
</html>