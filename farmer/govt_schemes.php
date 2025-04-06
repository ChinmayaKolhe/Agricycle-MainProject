<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer') {
    header("Location: ../auth/login.php");
    exit();
}

include '../config/db_connect.php';
include '../farmer/header.php';

$farmer_id = $_SESSION['user_id'];

$query = "SELECT bp.id, bp.name, bp.pdf_path, bp.bank_link, bp.agent_id, ia.agency AS agent_name 
          FROM bank_policies bp
          JOIN insurance_agents ia ON bp.agent_id = ia.id";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Government Schemes | AgriCycle</title>
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
            background-color: #f5f5f5;
            background-image: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=1000');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            background-blend-mode: overlay;
            background-color: rgba(245, 245, 245, 0.9);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .schemes-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .page-header {
            position: relative;
            margin-bottom: 40px;
        }
        
        .page-header h2 {
            font-weight: 700;
            color: var(--dark-green);
            position: relative;
            display: inline-block;
        }
        
        .page-header h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-green), var(--light-green));
        }
        
        .page-header p {
            color: #666;
            font-size: 1.1rem;
        }
        
        .scheme-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }
        
        .scheme-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15);
        }
        
        .scheme-header {
            background: linear-gradient(90deg, var(--primary-green), var(--light-green));
            color: white;
            padding: 15px 20px;
        }
        
        .scheme-header h5 {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .scheme-body {
            padding: 20px;
        }
        
        .scheme-description {
            color: #555;
            margin-bottom: 15px;
        }
        
        .scheme-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }
        
        .scheme-agency {
            background-color: #f5f5f5;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 500;
            color: var(--earth-brown);
        }
        
        .scheme-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .btn-scheme {
            padding: 8px 15px;
            border-radius: 25px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-scheme:hover {
            transform: translateY(-2px);
        }
        
        .btn-download {
            background-color: var(--light-green);
            color: white;
            border: none;
        }
        
        .btn-download:hover {
            background-color: var(--primary-green);
        }
        
        .btn-apply {
            background-color: var(--harvest-orange);
            color: white;
            border: none;
        }
        
        .btn-apply:hover {
            background-color: #e65100;
        }
        
        .btn-bank {
            background-color: var(--primary-green);
            color: white;
            border: none;
        }
        
        .btn-bank:hover {
            background-color: var(--dark-green);
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .empty-state i {
            font-size: 3.5rem;
            color: var(--light-green);
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            color: var(--dark-green);
            margin-bottom: 15px;
        }
        
        .empty-state p {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 25px;
        }
        
        @media (max-width: 768px) {
            .scheme-actions {
                flex-direction: column;
            }
            
            .btn-scheme {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
<div class="schemes-container">
    <div class="page-header animate__animated animate__fadeIn">
        <h2><i class="bi bi-file-earmark-text"></i> Government Schemes & Policies</h2>
        <p>Explore various agricultural policies and apply directly through our platform</p>
    </div>
    
    <?php if (mysqli_num_rows($result) == 0): ?>
        <div class="empty-state animate__animated animate__fadeIn">
            <i class="bi bi-info-circle"></i>
            <h3>No Schemes Available</h3>
            <p>There are currently no government schemes listed. Please check back later or contact your local agricultural office.</p>
        </div>
    <?php else: ?>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-6 animate__animated animate__fadeInUp">
                    <div class="scheme-card">
                        <div class="scheme-header">
                            <h5><?= htmlspecialchars($row['name']) ?></h5>
                        </div>
                        <div class="scheme-body">
                            <p class="scheme-description">
                                <?= htmlspecialchars($row['description'] ?? 'No description available') ?>
                            </p>
                            
                            <div class="scheme-meta">
                                <span class="scheme-agency">
                                    <i class="bi bi-building"></i> <?= htmlspecialchars($row['agent_name']) ?>
                                </span>
                            </div>
                            
                            <div class="scheme-actions">
                                <?php if ($row['pdf_path']): ?>
                                    <a href="download_pdf.php?id=<?= $row['id'] ?>" class="btn-scheme btn-download">
                                        <i class="bi bi-download"></i> Download PDF
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($row['bank_link']): ?>
                                    <a href="<?= htmlspecialchars($row['bank_link']) ?>" target="_blank" class="btn-scheme btn-bank">
                                        <i class="bi bi-bank"></i> Bank Portal
                                    </a>
                                <?php endif; ?>
                                
                                <form action="apply_policy.php" method="POST" class="d-inline">
                                    <input type="hidden" name="policy_id" value="<?= $row['id'] ?>">
                                    <input type="hidden" name="agent_id" value="<?= $row['agent_id'] ?>">
                                    <button type="submit" class="btn-scheme btn-apply">
                                        <i class="bi bi-send"></i> Apply Now
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Initialize animations on scroll
    document.addEventListener('DOMContentLoaded', function() {
        const animateOnScroll = function() {
            const elements = document.querySelectorAll('.animate__animated');
            
            elements.forEach(element => {
                const elementPosition = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                
                if (elementPosition < windowHeight - 100) {
                    const animationClass = element.classList[1];
                    element.classList.add(animationClass);
                }
            });
        };
        
        window.addEventListener('scroll', animateOnScroll);
        animateOnScroll(); // Run once on load
    });
</script>
</body>
</html>