<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer') {
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('Please login as farmer to view policy status', 'error');
                setTimeout(() => {
                    window.location.href = '../auth/login.php';
                }, 2500);
            });
          </script>";
    exit();
}

include '../config/db_connect.php';

$farmer_id = $_SESSION['user_id'];

$query = "
    SELECT 
        pr.status, pr.applied_at,
        p.name,
        a.agency AS agency_name
    FROM policy_requests pr
    JOIN bank_policies p ON pr.policy_id = p.id
    JOIN insurance_agents a ON pr.agent_id = a.id
    WHERE pr.farmer_id = ?
";

$stmt = mysqli_prepare($conn, $query);
if (!$stmt) {
    die("<script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('Database error occurred', 'error');
            });
          </script>");
}

mysqli_stmt_bind_param($stmt, 'i', $farmer_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Policy Application Status | AgriCycle</title>
    <style>
        :root {
            --primary-green: #4CAF50;
            --dark-green: #388E3C;
            --light-green: #C8E6C9;
            --soil-brown: #8D6E63;
            --harvest-gold: #FFD54F;
            --pending-orange: #FF9800;
            --approved-green: #4CAF50;
            --rejected-red: #F44336;
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
            animation: slideIn 0.5s forwards;
            overflow: hidden;
        }
        
        .notification.success {
            background-color: var(--approved-green);
        }
        
        .notification.error {
            background-color: var(--rejected-red);
        }
        
        .notification.warning {
            background-color: var(--pending-orange);
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
        
        .policy-container {
            max-width: 1000px;
            margin: 60px auto;
            padding: 30px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transform: translateY(20px);
            opacity: 0;
            animation: fadeInUp 0.6s forwards 0.2s;
        }
        
        @keyframes fadeInUp {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .policy-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        
        .policy-title {
            color: var(--primary-green);
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .policy-subtitle {
            color: var(--soil-brown);
            font-size: 16px;
        }
        
        .policy-icon {
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
        
        .policy-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            overflow: hidden;
            border-left: 5px solid var(--primary-green);
            transition: all 0.3s ease;
        }
        
        .policy-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .policy-card-header {
            padding: 15px 20px;
            background: rgba(76, 175, 80, 0.1);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .policy-name {
            font-weight: 600;
            font-size: 18px;
            color: var(--primary-green);
        }
        
        .policy-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }
        
        .status-pending {
            background-color: rgba(255, 152, 0, 0.1);
            color: var(--pending-orange);
        }
        
        .status-approved {
            background-color: rgba(76, 175, 80, 0.1);
            color: var(--approved-green);
        }
        
        .status-rejected {
            background-color: rgba(244, 67, 54, 0.1);
            color: var(--rejected-red);
        }
        
        .policy-card-body {
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .policy-detail {
            flex: 1;
            min-width: 200px;
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--soil-brown);
            margin-bottom: 5px;
            font-size: 14px;
        }
        
        .detail-value {
            font-size: 16px;
        }
        
        .policy-date {
            font-size: 14px;
            color: var(--soil-brown);
            text-align: right;
            padding: 10px 20px;
            background: rgba(0, 0, 0, 0.02);
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .no-policies {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .no-policies-icon {
            font-size: 60px;
            color: var(--light-green);
            margin-bottom: 20px;
        }
        
        .no-policies-text {
            font-size: 18px;
            color: var(--soil-brown);
            margin-bottom: 20px;
        }
        
        .explore-btn {
            background: var(--primary-green);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .explore-btn:hover {
            background: var(--dark-green);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
            color: white;
        }
        
        @media (max-width: 768px) {
            .policy-container {
                padding: 20px;
                margin: 30px 15px;
            }
            
            .policy-card-body {
                flex-direction: column;
                gap: 15px;
            }
            
            .policy-date {
                text-align: left;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="notification-container" id="notificationContainer"></div>
    
    <div class="policy-container">
        <div class="policy-header">
            <div class="policy-icon">
                <i class="fas fa-file-contract"></i>
            </div>
            <h1 class="policy-title">Your Policy Applications</h1>
            <p class="policy-subtitle">Track the status of your insurance applications</p>
        </div>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="policy-card">
                    <div class="policy-card-header">
                        <div class="policy-name"><?= htmlspecialchars($row['name']) ?></div>
                        <div class="policy-status <?= 'status-' . strtolower($row['status']) ?>">
                            <?= htmlspecialchars($row['status']) ?>
                        </div>
                    </div>
                    
                    <div class="policy-card-body">
                        <div class="policy-detail">
                            <div class="detail-label">Insurance Agency</div>
                            <div class="detail-value"><?= htmlspecialchars($row['agency_name']) ?></div>
                        </div>
                        
                        <div class="policy-detail">
                            <div class="detail-label">Application Date</div>
                            <div class="detail-value"><?= date('F j, Y', strtotime($row['applied_at'])) ?></div>
                        </div>
                    </div>
                    
                    <div class="policy-date">
                        Last updated: <?= date('M j, Y \a\t g:i A', strtotime($row['applied_at'])) ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-policies">
                <div class="no-policies-icon">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3>No Policy Applications</h3>
                <p class="no-policies-text">You haven't applied for any insurance policies yet.</p>
                <a href="../insurance/policies.php" class="explore-btn">
                    <i class="fas fa-search"></i> Explore Policies
                </a>
            </div>
        <?php endif; ?>
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
                case 'warning':
                    icon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
                    break;
            }
            
            const text = document.createElement('span');
            text.textContent = message;
            
            notification.appendChild(icon);
            notification.appendChild(text);
            container.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'fadeOut 0.5s forwards';
                setTimeout(() => {
                    notification.remove();
                }, 500);
            }, 3000);
        }
        
        // Add animation to policy cards when they come into view
        document.addEventListener('DOMContentLoaded', function() {
            const policyCards = document.querySelectorAll('.policy-card');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });
            
            policyCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = `all 0.5s ease ${index * 0.1}s`;
                observer.observe(card);
            });
        });
    </script>
</body>
</html>