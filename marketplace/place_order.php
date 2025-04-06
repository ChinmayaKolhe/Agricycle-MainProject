<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'buyer') {
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('Please login as buyer to make purchases', 'warning');
                setTimeout(() => {
                    window.location.href='../auth/login.php';
                }, 2500);
            });
          </script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = $_POST['item_id'];
    $quantity = (int)$_POST['quantity'];
    $total_cost = $_POST['total_cost'];
    $buyer_id = $_SESSION['user_id'];

    // Fetch item
    $query = "SELECT * FROM marketplace_items WHERE id = '$item_id'";
    $result = mysqli_query($conn, $query);
    $item = mysqli_fetch_assoc($result);

    if (!$item) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification('Item not found', 'error');
                    setTimeout(() => {
                        window.location.href='../dashboard/buyer_dashboard.php';
                    }, 2500);
                });
              </script>";
        exit();
    }

    if ($quantity > $item['quantity']) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification('Cannot buy more than available quantity', 'error');
                    setTimeout(() => {
                        window.location.href='buy_item.php?id=$item_id';
                    }, 2500);
                });
              </script>";
        exit();
    }

    // Update stock
    $new_quantity = $item['quantity'] - $quantity;
    mysqli_query($conn, "UPDATE marketplace_items SET quantity = $new_quantity WHERE id = $item_id");

    // Insert into orders table
    $insert_query = "INSERT INTO orders (buyer_id, item_id, quantity, total_price)
                     VALUES ('$buyer_id', '$item_id', '$quantity', '$total_cost')";
    mysqli_query($conn, $insert_query);

    // Fetch farmer email
    $farmer_id = $item['user_id'];
    $farmer_query = "SELECT email FROM farmers WHERE id = '$farmer_id'";
    $farmer_result = mysqli_query($conn, $farmer_query);
    $farmer = mysqli_fetch_assoc($farmer_result);

    if ($farmer) {
        $farmer_email = $farmer['email'];
        $item_name = $item['item_name'];
        $message = "Your item '<b>$item_name</b>' has been purchased. Quantity: $quantity. Total: ₹$total_cost.";

        // Insert notification
        $notif_query = "INSERT INTO notifications (user_email, message, is_read, created_at) 
                        VALUES ('$farmer_email', '$message', 0, NOW())";
        mysqli_query($conn, $notif_query);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Confirmation</title>
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
            --text-dark: #333;
            --text-light: #f5f5f5;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            background-image: linear-gradient(to bottom, rgba(201, 255, 203, 0.3), rgba(255, 255, 255, 0.8)), 
                              url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><path fill="%234CAF50" fill-opacity="0.1" d="M30,10 Q50,0 70,10 Q90,20 80,40 Q70,60 50,70 Q30,60 20,40 Q10,20 30,10 Z"/></svg>');
            background-size: 200px;
            color: var(--text-dark);
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
            animation: slideIn 0.5s forwards;
            overflow: hidden;
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
            color: var(--text-dark);
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
        
        .invoice-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 700px;
            margin: 40px auto;
            padding: 30px;
            position: relative;
            overflow: hidden;
            transform: translateY(20px);
            opacity: 0;
            animation: fadeInUp 0.6s forwards 0.3s;
        }
        
        .invoice-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 8px;
            height: 100%;
            background: var(--primary-green);
        }
        
        @keyframes fadeInUp {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        
        .invoice-title {
            color: var(--primary-green);
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .invoice-subtitle {
            color: var(--soil-brown);
            font-size: 16px;
        }
        
        .invoice-icon {
            font-size: 50px;
            color: var(--primary-green);
            margin-bottom: 20px;
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
        
        .invoice-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .detail-group {
            margin-bottom: 15px;
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--soil-brown);
            margin-bottom: 5px;
            display: block;
        }
        
        .detail-value {
            font-size: 16px;
            padding: 8px 12px;
            background: var(--light-green);
            border-radius: 6px;
            border-left: 3px solid var(--primary-green);
        }
        
        .price-highlight {
            font-size: 24px;
            color: var(--primary-green);
            font-weight: 700;
        }
        
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 25px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-primary {
            background: var(--primary-green);
            color: white;
            border: 2px solid var(--primary-green);
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
        
        .btn-icon {
            margin-right: 8px;
            font-size: 18px;
        }
        
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: var(--primary-green);
            opacity: 0;
        }
        
        @media (max-width: 768px) {
            .invoice-details {
                grid-template-columns: 1fr;
            }
            
            .invoice-container {
                padding: 20px;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="notification-container" id="notificationContainer"></div>
    
    <div class="invoice-container">
        <div class="invoice-header">
            <div class="invoice-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="invoice-title">Purchase Confirmed!</h1>
            <p class="invoice-subtitle">Thank you for supporting local farmers</p>
        </div>
        
        <div class="invoice-details">
            <div class="detail-group">
                <span class="detail-label">Item Name</span>
                <div class="detail-value"><?php echo htmlspecialchars($item['item_name']); ?></div>
            </div>
            
            <div class="detail-group">
                <span class="detail-label">Description</span>
                <div class="detail-value"><?php echo htmlspecialchars($item['description']); ?></div>
            </div>
            
            <div class="detail-group">
                <span class="detail-label">Unit Price</span>
                <div class="detail-value">₹<?php echo htmlspecialchars($item['price']); ?></div>
            </div>
            
            <div class="detail-group">
                <span class="detail-label">Quantity Purchased</span>
                <div class="detail-value"><?php echo htmlspecialchars($quantity); ?></div>
            </div>
            
            <div class="detail-group">
                <span class="detail-label">Seller Contact</span>
                <div class="detail-value"><?php echo htmlspecialchars($item['contact_info']); ?></div>
            </div>
            
            <div class="detail-group">
                <span class="detail-label">Purchase Date</span>
                <div class="detail-value"><?php echo date("F j, Y g:i a"); ?></div>
            </div>
        </div>
        
        <div style="text-align: center; margin: 25px 0;">
            <span class="detail-label">Total Amount Paid</span>
            <div class="price-highlight">₹<?php echo htmlspecialchars($total_cost); ?></div>
        </div>
        
        <div class="action-buttons">
            <a href="../dashboard/buyer_dashboard.php" class="btn btn-primary">
                <i class="fas fa-home btn-icon"></i> Back to Home
            </a>
            <a href="#" class="btn btn-outline" onclick="window.print()">
                <i class="fas fa-print btn-icon"></i> Print Invoice
            </a>
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
                    icon.innerHTML = '<i class="fas fa-check-circle"></i>';
                    break;
                case 'error':
                    icon.innerHTML = '<i class="fas fa-times-circle"></i>';
                    break;
                case 'warning':
                    icon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
                    break;
                case 'info':
                    icon.innerHTML = '<i class="fas fa-info-circle"></i>';
                    break;
            }
            
            const text = document.createElement('span');
            text.textContent = message;
            
            notification.appendChild(icon);
            notification.appendChild(text);
            container.appendChild(notification);
            
            // Remove notification after animation completes
            setTimeout(() => {
                notification.style.animation = 'fadeOut 0.5s forwards';
                setTimeout(() => {
                    notification.remove();
                }, 500);
            }, 2500);
        }
        
        // Create confetti effect
        function createConfetti() {
            const colors = ['#4CAF50', '#8BC34A', '#CDDC39', '#FFC107', '#FF9800'];
            const container = document.querySelector('.invoice-container');
            
            for (let i = 0; i < 50; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.left = Math.random() * 100 + '%';
                confetti.style.top = -10 + 'px';
                confetti.style.transform = `rotate(${Math.random() * 360}deg)`;
                confetti.style.width = Math.random() * 8 + 5 + 'px';
                confetti.style.height = Math.random() * 8 + 5 + 'px';
                container.appendChild(confetti);
                
                const animationDuration = Math.random() * 3 + 2;
                
                confetti.style.animation = `fall ${animationDuration}s linear forwards`;
                confetti.style.opacity = '1';
                
                // Define keyframes for falling animation
                const keyframes = `
                    @keyframes fall {
                        to {
                            top: 100%;
                            left: ${Math.random() * 100}%;
                            opacity: 0;
                        }
                    }
                `;
                
                // Add keyframes to head if not already present
                if (!document.getElementById('confetti-animation')) {
                    const style = document.createElement('style');
                    style.id = 'confetti-animation';
                    style.innerHTML = keyframes;
                    document.head.appendChild(style);
                }
                
                // Remove confetti after animation
                setTimeout(() => {
                    confetti.remove();
                }, animationDuration * 1000);
            }
        }
        
        // Trigger confetti on page load
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(createConfetti, 500);
        });
    </script>
</body>
</html>