<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'farmer') {
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('Access Denied: Only farmers can add items', 'error');
                setTimeout(() => {
                    window.location.href = '../auth/login.php';
                }, 2500);
            });
          </script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $contact_info = mysqli_real_escape_string($conn, $_POST['contact_info']);
    $seller_id = $_SESSION['user_id'];

    // File upload logic
    $photo_path = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['photo']['tmp_name'];
        $fileName = $_FILES['photo']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($fileExtension, $allowedExts)) {
            $newFileName = uniqid("waste_") . '.' . $fileExtension;
            $uploadFileDir = '../uploads/wasteimg/';

            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0777, true);
            }

            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $photo_path = 'uploads/wasteimg/' . $newFileName;
            } else {
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showNotification('Error moving the uploaded file', 'error');
                        });
                      </script>";
                exit();
            }
        } else {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showNotification('Invalid file type. Only JPG, PNG, or WEBP allowed', 'error');
                    });
                  </script>";
            exit();
        }
    }

    $query = "INSERT INTO marketplace_items (item_name, description, price, quantity, contact_info, user_id, photo_path) 
              VALUES ('$item_name', '$description', '$price', '$quantity', '$contact_info', '$seller_id', '$photo_path')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification('Item added successfully! Redirecting...', 'success');
                    setTimeout(() => {
                        window.location.href = '../marketplace/index.php';
                    }, 2000);
                });
              </script>";
        exit();
    } else {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification('Database error: " . addslashes(mysqli_error($conn)) . "', 'error');
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
    <title>Add Item | AgriCycle</title>
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
        
        .add-item-container {
            max-width: 700px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
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
        
        @media (max-width: 768px) {
            .add-item-container {
                padding: 20px;
                margin: 20px;
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
    
    
    <div class="add-item-container">
        <div class="form-header">
            <div class="form-icon">
                <i class="fas fa-plus-circle"></i>
            </div>
            <h1 class="form-title">List Your Agricultural Waste</h1>
            <p class="form-subtitle">Connect with buyers looking for your materials</p>
        </div>
        
        <form method="POST" enctype="multipart/form-data" id="itemForm">
            <div class="form-group">
                <label class="form-label" for="item_name">Item Name</label>
                <input type="text" id="item_name" name="item_name" class="form-control" placeholder="e.g., Rice Husk, Banana Stems, Coconut Shells" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="description">Detailed Description</label>
                <textarea id="description" name="description" class="form-control" placeholder="Describe the item, condition, possible uses, etc." required></textarea>
            </div>
            
            <div class="form-group price-input">
                <label class="form-label" for="price">Price per kg (₹)</label>
                <input type="number" id="price" name="price" class="form-control" min="0" step="0.01" placeholder="Enter price" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="quantity">Available Quantity (kg)</label>
                <input type="number" id="quantity" name="quantity" class="form-control" min="1" placeholder="Enter quantity in kilograms" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="contact_info">Contact Information</label>
                <input type="text" id="contact_info" name="contact_info" class="form-control" placeholder="Phone number or email for buyers to contact you" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Upload Item Photo</label>
                <div class="file-input-container">
                    <div class="file-input-button" id="fileInputButton">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>Click to upload photo</span>
                        <small>JPG, PNG, or WEBP (Max 5MB)</small>
                        <div class="file-name" id="fileName"></div>
                    </div>
                    <input type="file" name="photo" id="photo" class="file-input" accept="image/*" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-leaf"></i> List Item
            </button>
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
        
        // File input handling
        const fileInput = document.getElementById('photo');
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
        document.getElementById('itemForm').addEventListener('submit', function() {
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            submitButton.disabled = true;
        });
    </script>
</body>
</html>