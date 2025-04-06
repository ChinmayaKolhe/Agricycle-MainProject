<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer') {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/db_connect.php';
include '../farmer/header.php';

$farmer_id = $_SESSION['user_id'];

// Fetch unread notifications
$notif_query = "SELECT * FROM notifications WHERE user_id = '$farmer_id' AND is_read = 0 ORDER BY created_at DESC";
$result = mysqli_query($conn, $notif_query);

// Waste chart data
$waste_data = [
    'Crop Residue' => 0,
    'Animal Manure' => 0,
    'Fruit and Vegetable Waste' => 0,
    'Agrochemical Containers' => 0,
    'Plastic Mulch' => 0,
    'Spoiled Grain' => 0,
    'Weeds & Grass' => 0
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Dashboard | AgriCycle</title>
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
            --sky-blue: #4fc3f7;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            background-image: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            background-blend-mode: overlay;
            background-color: rgba(248, 249, 250, 0.9);
        }
        
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .welcome-header {
            background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(236,253,245,0.9));
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-left: 5px solid var(--primary-green);
        }
        
        .welcome-header h2 {
            font-weight: 700;
            color: var(--dark-green);
            position: relative;
            display: inline-block;
            margin-bottom: 10px;
        }
        
        .welcome-header h2::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-green), var(--light-green));
            border-radius: 3px;
        }
        
        .welcome-header p {
            color: #4a5568;
            font-size: 1.1rem;
            max-width: 800px;
        }
        
        .dashboard-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
            border: none;
            position: relative;
            overflow: hidden;
        }
        
        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-green), var(--light-green));
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15);
        }
        
        .dashboard-card .card-body {
            padding: 25px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            transition: all 0.3s;
        }
        
        .dashboard-card:hover .card-icon {
            transform: scale(1.1);
        }
        
        .dashboard-card .card-title {
            font-weight: 600;
            color: var(--dark-green);
            margin-bottom: 15px;
        }
        
        .dashboard-card .btn {
            margin-top: auto;
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s;
            border-width: 2px;
        }
        
        .dashboard-card .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .schemes-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            transition: all 0.3s;
            height: 100%;
            border-left: 5px solid var(--sky-blue);
        }
        
        .schemes-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15);
        }
        
        .schemes-card h5 {
            color: var(--dark-green);
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .schemes-card h5 i {
            margin-right: 10px;
            color: var(--sky-blue);
        }
        
        .waste-tips-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            border-left: 5px solid var(--harvest-orange);
        }
        
        .waste-tips-container h3 {
            color: var(--dark-green);
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .waste-tips-container h3 i {
            margin-right: 10px;
        }
        
        .alert-info {
            background-color: #f0fdf4;
            border-left: 4px solid var(--primary-green);
            color: #1f2937;
            transition: all 0.3s;
            margin-bottom: 15px;
            border-radius: 8px;
            padding: 15px;
            position: relative;
            overflow: hidden;
        }
        
        .alert-info::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--primary-green);
        }
        
        .alert-info:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .alert-info strong {
            color: var(--earth-brown);
        }
        
        /* Floating animation */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
            100% { transform: translateY(0px); }
        }
        
        .floating-icon {
            animation: float 3s ease-in-out infinite;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 15px;
            }
            
            .welcome-header {
                padding: 20px;
            }
            
            .dashboard-card {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <div class="welcome-header animate__animated animate__fadeIn">
        <h2>Welcome, Farmer!</h2>
        <p>Manage your agricultural waste, explore the marketplace, and connect with the farming community to maximize your sustainability efforts.</p>
    </div>

    <!-- Dashboard Cards -->
    <div class="row g-4 mt-2">
        <div class="col-md-4 animate__animated animate__fadeInUp" data-aos-delay="100">
            <div class="dashboard-card">
                <div class="card-body">
                    <div class="card-icon floating-icon">
                        <i class="bi bi-cart"></i>
                    </div>
                    <h5 class="card-title">Marketplace</h5>
                    <p class="text-muted">Sell your agricultural waste or buy compost from other farmers</p>
                    <a href="../marketplace/index.php" class="btn btn-outline-success">
                        <i class="bi bi-arrow-right"></i> Explore Marketplace
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 animate__animated animate__fadeInUp" data-aos-delay="200">
            <div class="dashboard-card">
                <div class="card-body">
                    <div class="card-icon floating-icon">
                        <i class="bi bi-droplet-fill"></i>
                    </div>
                    <h5 class="card-title">Irrigation Guide</h5>
                    <p class="text-muted">Optimize your water usage with smart irrigation techniques</p>
                    <a href="../farmer/irrigation_guide.php" class="btn btn-outline-info">
                        <i class="bi bi-arrow-right"></i> Water Management
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 animate__animated animate__fadeInUp" data-aos-delay="300">
            <div class="dashboard-card">
                <div class="card-body">
                    <div class="card-icon floating-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h5 class="card-title">Community Forum</h5>
                    <p class="text-muted">Connect with other farmers and share knowledge</p>
                    <a href="../community/community_forum.php" class="btn btn-outline-warning">
                        <i class="bi bi-arrow-right"></i> Join Community
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart + Schemes -->
    <div class="row mt-4">
        <div class="col-md-6 animate__animated animate__fadeInUp" data-aos-delay="400">
            <div class="schemes-card">
                <h5><i class="bi bi-file-earmark-text"></i> Government Schemes</h5>
                <p class="text-muted">Check out the latest subsidies, policies, and support programs available for farmers</p>
                <a href="../farmer/govt_schemes.php" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-right"></i> View Available Schemes
                </a>
            </div>
        </div>
        
        <div class="col-md-6 animate__animated animate__fadeInUp" data-aos-delay="500">
            <div class="waste-tips-container">
                <h3><i class="bi bi-recycle"></i> Agri-Waste Management Tips</h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="alert-info">
                            <strong>Compost Organic Waste:</strong> Convert crop residues and cow dung into nutrient-rich compost.
                        </div>
                        <div class="alert-info">
                            <strong>Reuse Crop Residue:</strong> Use leftovers as mulch or livestock bedding instead of burning.
                        </div>
                        <div class="alert-info">
                            <strong>Avoid Burning:</strong> Causes air pollution and loss of soil nutrients.
                        </div>
                        <div class="alert-info">
                            <strong>Separate Plastic Waste:</strong> Keep fertilizer bags and wrappers for recycling.
                        </div>
                        <div class="alert-info">
                            <strong>Dry and Store:</strong> Keep dry waste organized for easy pickup.
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="alert-info">
                            <strong>E-Waste Management:</strong> Donate old electric pumps via e-waste pickup.
                        </div>
                        <div class="alert-info">
                            <strong>Recycle Containers:</strong> Clean pesticide containers for recycling.
                        </div>
                        <div class="alert-info">
                            <strong>Earn Rewards:</strong> Get cashback for verified agri-waste pickups.
                        </div>
                        <div class="alert-info">
                            <strong>Share Ideas:</strong> Post waste reuse ideas in the community forum.
                        </div>
                        <div class="alert-info">
                            <strong>AI Classifier:</strong> Upload waste photos to identify recyclables.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Initialize animations on scroll
document.addEventListener('DOMContentLoaded', function() {
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.animate__animated');
        
        elements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (elementPosition < windowHeight - 100) {
                const animationClass = element.getAttribute('data-aos');
                if (animationClass) {
                    element.classList.add(animationClass);
                }
            }
        });
    };
    
    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Run once on load
});

// Waste Chart
const ctx = document.getElementById('wasteChart')?.getContext('2d');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                'Crop Residue', 'Animal Manure', 'Fruit and Vegetable Waste',
                'Agrochemical Containers', 'Plastic Mulch', 'Spoiled Grain', 'Weeds & Grass'
            ],
            datasets: [{
                label: 'Waste in Kg',
                data: [
                    <?= $waste_data['Crop Residue'] ?>,
                    <?= $waste_data['Animal Manure'] ?>,
                    <?= $waste_data['Fruit and Vegetable Waste'] ?>,
                    <?= $waste_data['Agrochemical Containers'] ?>,
                    <?= $waste_data['Plastic Mulch'] ?>,
                    <?= $waste_data['Spoiled Grain'] ?>,
                    <?= $waste_data['Weeds & Grass'] ?>
                ],
                backgroundColor: [
                    '#4caf50','#795548','#ff9800','#9c27b0',
                    '#2196f3','#f44336','#8bc34a'
                ]
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } },
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: '#333' }
            }
        }
    });
}

// AJAX Mark All Notifications as Read
document.getElementById('markAllRead')?.addEventListener('click', () => {
    fetch('mark_notifications_read.php', { method: 'POST' })
    .then(response => response.text())
    .then(() => {
        document.getElementById('notificationList').innerHTML = '<p>All notifications marked as read.</p>';
    });
});
</script>
</body>
</html>