<?php
session_start();
include '../config/db_connect.php';
include '../marketplace/navbar.php';

// Fetch marketplace items with quantity > 0 and include photo_path
$query = "SELECT id, item_name, description, price, quantity, contact_info, user_id AS seller_id, photo_path 
          FROM marketplace_items 
          WHERE quantity > 0 
          ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching items: " . mysqli_error($conn));
}

$items = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Marketplace | AgriCycle</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        :root {
            --primary-green: #2e7d32;
            --light-green: #81c784;
            --dark-green: #1b5e20;
            --earth-brown: #5d4037;
            --sun-yellow: #ffd54f;
            --sky-blue: #4fc3f7;
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
        
        .container {
            max-width: 1400px;
            padding-top: 20px;
        }
        
        .page-header {
            position: relative;
            margin-bottom: 40px;
            text-align: center;
        }
        
        .page-header h2 {
            font-weight: 700;
            color: var(--dark-green);
            position: relative;
            display: inline-block;
            padding-bottom: 10px;
        }
        
        .page-header h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-green), var(--light-green));
            border-radius: 2px;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            height: 100%;
        }
        
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 20px rgba(0,0,0,0.15);
        }
        
        .card-img-top {
            height: 220px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .card:hover .card-img-top {
            transform: scale(1.03);
        }
        
        .card-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
        }
        
        .card-title {
            color: var(--dark-green);
            font-weight: 600;
            margin-bottom: 0.75rem;
        }
        
        .card-text {
            margin-bottom: 0.5rem;
            color: #555;
            font-size: 0.95rem;
        }
        
        .card-text strong {
            color: var(--earth-brown);
        }
        
        .price-tag {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-green);
            margin: 0.5rem 0;
        }
        
        .btn-group {
            margin-top: auto;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .btn {
            border-radius: 25px;
            padding: 8px 16px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s;
            flex-grow: 1;
            text-align: center;
        }
        
        .btn-primary {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
        }
        
        .btn-primary:hover {
            background-color: var(--dark-green);
            border-color: var(--dark-green);
            transform: translateY(-2px);
        }
        
        .btn-success {
            background-color: var(--harvest-orange);
            border-color: var(--harvest-orange);
        }
        
        .btn-success:hover {
            background-color: #e65100;
            border-color: #e65100;
            transform: translateY(-2px);
        }
        
        .btn-outline-danger {
            color: #e53935;
            border-color: #e53935;
        }
        
        .btn-outline-danger:hover {
            background-color: #e53935;
            border-color: #e53935;
            transform: translateY(-2px);
        }
        
        .btn-warning {
            background-color: var(--sun-yellow);
            border-color: var(--sun-yellow);
            color: #333;
        }
        
        .btn-warning:hover {
            background-color: #ffb300;
            border-color: #ffb300;
            transform: translateY(-2px);
            color: #333;
        }
        
        .btn-danger {
            background-color: #e53935;
            border-color: #e53935;
        }
        
        .btn-danger:hover {
            background-color: #c62828;
            border-color: #c62828;
            transform: translateY(-2px);
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            margin: 20px 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .empty-state i {
            font-size: 3rem;
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
            margin-bottom: 20px;
        }
        
        .search-filter {
            background-color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .badge-organic {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: var(--primary-green);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 1;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .quantity-indicator {
            position: absolute;
            top: 15px;
            left: 15px;
            background-color: rgba(0,0,0,0.6);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 1;
        }
        
        .animate-delay-1 {
            animation-delay: 0.1s;
        }
        
        .animate-delay-2 {
            animation-delay: 0.2s;
        }
        
        .animate-delay-3 {
            animation-delay: 0.3s;
        }
        
        @media (max-width: 768px) {
            .card {
                margin-bottom: 20px;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
        
        /* Floating animation */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .floating-card {
            animation: float 4s ease-in-out infinite;
        }
        
        /* Pulse animation */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(46, 125, 50, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(46, 125, 50, 0); }
            100% { box-shadow: 0 0 0 0 rgba(46, 125, 50, 0); }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="page-header animate__animated animate__fadeIn">
        <h2>🌱 Marketplace</h2>
        <p class="lead text-muted">Discover agricultural waste products and sustainable solutions</p>
    </div>

    <!-- Search and Filter Section -->
    <div class="search-filter animate__animated animate__fadeIn">
        <div class="row">
            <div class="col-md-8">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-success text-white"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search for items...">
                    <button class="btn btn-success" type="button">Search</button>
                </div>
            </div>
            <div class="col-md-4">
                <select class="form-select">
                    <option selected>Filter by Category</option>
                    <option>Organic Waste</option>
                    <option>Recyclable Materials</option>
                    <option>Compost</option>
                    <option>Farm Equipment</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Marketplace Items -->
    <div class="row">
        <?php if (empty($items)): ?>
            <div class="col-12">
                <div class="empty-state animate__animated animate__fadeIn">
                    <i class="bi bi-binoculars"></i>
                    <h3>No Items Available</h3>
                    <p>There are currently no items listed in the marketplace. Check back later or consider listing your own items.</p>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'farmer'): ?>
                        <a href="add_item.php" class="btn btn-success btn-lg">List Your First Item</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($items as $index => $item): ?>
                <div class="col-md-4 mb-4 animate__animated animate__fadeInUp animate-delay-<?= $index % 3 ?>">
                    <div class="card shadow-sm h-100 floating-card">
                        <?php if (!empty($item['photo_path'])): ?>
                            <div class="position-relative">
                                <img src="../<?= htmlspecialchars($item['photo_path']) ?>" class="card-img-top" alt="Item Image">
                                <span class="badge-organic">Organic</span>
                                <span class="quantity-indicator"><?= htmlspecialchars($item['quantity']) ?> in stock</span>
                            </div>
                        <?php else: ?>
                            <div class="position-relative">
                                <img src="../assets/no-image.png" class="card-img-top" alt="No Image">
                                <span class="quantity-indicator"><?= htmlspecialchars($item['quantity']) ?> in stock</span>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($item['item_name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($item['description']) ?></p>
                            <div class="price-tag">₹<?= htmlspecialchars($item['price']) ?></div>
                            <p class="card-text"><small class="text-muted"><i class="bi bi-telephone"></i> <?= htmlspecialchars($item['contact_info']) ?></small></p>
                            
                            <div class="btn-group">
                                <a href="view_item.php?id=<?= $item['id'] ?>" class="btn btn-primary"><i class="bi bi-eye"></i> Details</a>

                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'buyer'): ?>
                                    <a href="buy_item.php?id=<?= $item['id'] ?>" class="btn btn-success pulse"><i class="bi bi-cart"></i> Buy</a>
                                    <a href="add_to_wishlist.php?id=<?= $item['id'] ?>" class="btn btn-outline-danger"><i class="bi bi-heart"></i></a>
                                <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'farmer' && $_SESSION['user_id'] == $item['seller_id']): ?>
                                    <a href="edit_item.php?id=<?= $item['id'] ?>" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                                    <a href="delete_item.php?id=<?= $item['id'] ?>" class="btn btn-danger"><i class="bi bi-trash"></i> Delete</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
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