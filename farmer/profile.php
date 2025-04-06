<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer') {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/db_connect.php';
include 'header.php';

$farmer_id = $_SESSION['user_id'];

// Fetch farmer details
$query = "SELECT * FROM farmers WHERE id = '$farmer_id'";
$result = mysqli_query($conn, $query);
$farmer = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Profile | AgriCycle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
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
            background-color: #f8f9fa;
            background-image: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            background-blend-mode: overlay;
            background-color: rgba(248, 249, 250, 0.9);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .profile-container {
            max-width: 800px;
            margin: 2rem auto;
        }
        
        .profile-card {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            transition: all 0.3s ease;
        }
        
        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        
        .profile-header {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            padding: 2rem;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .profile-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            transform: rotate(30deg);
        }
        
        .profile-header h2 {
            position: relative;
            font-weight: 700;
            margin-bottom: 0;
        }
        
        .profile-header i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: inline-block;
        }
        
        .profile-body {
            padding: 2.5rem;
            background: white;
        }
        
        .profile-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin: -85px auto 1.5rem;
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
        }
        
        .profile-photo:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .default-photo {
            font-size: 5rem;
            color: var(--light-green);
            background: white;
            border-radius: 50%;
            padding: 1rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin: -85px auto 1.5rem;
            position: relative;
            z-index: 2;
            width: 150px;
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .profile-info {
            margin-bottom: 2rem;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            background-color: rgba(129, 199, 132, 0.1);
        }
        
        .info-item:hover {
            background-color: rgba(129, 199, 132, 0.2);
            transform: translateX(5px);
        }
        
        .info-icon {
            font-size: 1.5rem;
            margin-right: 1rem;
            color: var(--primary-green);
            min-width: 30px;
            text-align: center;
        }
        
        .info-content {
            flex-grow: 1;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--earth-brown);
            margin-bottom: 0.2rem;
            font-size: 0.9rem;
        }
        
        .info-value {
            font-size: 1.1rem;
            color: #333;
            word-break: break-word;
        }
        
        .edit-btn {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
        }
        
        .edit-btn i {
            margin-right: 0.5rem;
        }
        
        .edit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(46, 125, 50, 0.4);
            color: white;
            background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
        }
        
        @media (max-width: 768px) {
            .profile-container {
                padding: 0 1rem;
            }
            
            .profile-body {
                padding: 1.5rem;
            }
            
            .profile-photo, .default-photo {
                width: 120px;
                height: 120px;
                margin: -70px auto 1rem;
            }
            
            .info-item {
                flex-direction: column;
                align-items: flex-start;
                padding: 0.75rem;
            }
            
            .info-icon {
                margin-right: 0;
                margin-bottom: 0.5rem;
            }
        }
        
        /* Animation for profile elements */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-item {
            animation: fadeInUp 0.6s ease forwards;
        }
    </style>
</head>
<body>
<div class="profile-container animate__animated animate__fadeIn">
    <div class="profile-card">
        <div class="profile-header">
            <i class="bi bi-person-circle"></i>
            <h2>Farmer Profile</h2>
        </div>
        
        <div class="profile-body text-center">
            <!-- Profile Photo -->
            <div class="mb-4">
                <?php if (!empty($farmer['profile_photo']) && file_exists("../" . $farmer['profile_photo'])): ?>
                    <img src="../<?= $farmer['profile_photo'] ?>" alt="Profile Photo" class="profile-photo animate__animated animate__zoomIn">
                <?php else: ?>
                    <div class="default-photo animate__animated animate__zoomIn">
                        <i class="bi bi-person-circle"></i>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Profile Info -->
            <div class="profile-info">
                <div class="info-item animate-item" style="animation-delay: 0.1s">
                    <div class="info-icon">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Name</div>
                        <div class="info-value"><?= htmlspecialchars($farmer['name']) ?></div>
                    </div>
                </div>
                
                <div class="info-item animate-item" style="animation-delay: 0.2s">
                    <div class="info-icon">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Email</div>
                        <div class="info-value"><?= htmlspecialchars($farmer['email']) ?></div>
                    </div>
                </div>
                
                <div class="info-item animate-item" style="animation-delay: 0.3s">
                    <div class="info-icon">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Phone</div>
                        <div class="info-value"><?= htmlspecialchars($farmer['phone']) ?></div>
                    </div>
                </div>
                
                <div class="info-item animate-item" style="animation-delay: 0.4s">
                    <div class="info-icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Location</div>
                        <div class="info-value"><?= htmlspecialchars($farmer['location']) ?></div>
                    </div>
                </div>
            </div>

            <!-- Edit Button -->
            <a href="edit_profile.php" class="edit-btn animate__animated animate__fadeInUp animate__delay-1s">
                <i class="bi bi-pencil-square"></i> Edit Profile
            </a>
        </div>
    </div>
</div>

<script>
    // Add animation to elements when they come into view
    document.addEventListener('DOMContentLoaded', function() {
        const animateItems = document.querySelectorAll('.animate-item');
        
        animateItems.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            item.style.transition = `all 0.6s ease ${index * 0.1}s`;
            
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 100);
        });
    });
</script>
</body>
</html>