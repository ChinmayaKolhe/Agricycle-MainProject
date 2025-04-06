<?php
session_start();
include 'config/db_connect.php';

// Only allow buyers
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    echo "<script>alert('Unauthorized access!'); window.location.href = 'auth/login.php';</script>";
    exit;
}

$buyer_id = $_SESSION['user_id'];

// Fetch order details with product info
$query = "
    SELECT 
        o.id AS order_id,
        o.quantity,
        o.total_price,
        o.created_at,
        m.item_name,
        m.description,
        m.price,
        m.contact_info,
        m.photo_path
    FROM orders o
    JOIN marketplace_items m ON o.item_id = m.id
    WHERE o.buyer_id = $buyer_id
    ORDER BY o.created_at DESC
";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Error fetching orders: " . mysqli_error($conn));
}
$orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | AgriCycle</title>
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
        
        .navbar {
            background: linear-gradient(90deg, var(--primary-green), var(--dark-green)) !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .nav-link {
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .nav-link:hover {
            transform: translateY(-2px);
        }
        
        .nav-link.active {
            font-weight: 600;
            border-bottom: 3px solid var(--sun-yellow);
        }
        
        .orders-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
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
        
        .empty-state .btn {
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 500;
        }
        
        .order-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            transition: all 0.3s ease;
            animation: fadeIn 0.5s ease-out;
        }
        
        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.12);
        }
        
        .order-header {
            background: linear-gradient(90deg, var(--primary-green), var(--light-green));
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .order-id {
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .order-date {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .order-body {
            display: flex;
            padding: 0;
        }
        
        .order-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            padding: 15px;
        }
        
        .order-details {
            flex: 1;
            padding: 20px;
        }
        
        .order-title {
            font-weight: 600;
            color: var(--dark-green);
            margin-bottom: 10px;
            font-size: 1.2rem;
        }
        
        .order-description {
            color: #555;
            margin-bottom: 15px;
            font-size: 0.95rem;
        }
        
        .order-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        
        .meta-item {
            display: flex;
            flex-direction: column;
        }
        
        .meta-label {
            font-size: 0.8rem;
            color: #777;
            margin-bottom: 3px;
        }
        
        .meta-value {
            font-weight: 600;
            color: var(--earth-brown);
        }
        
        .order-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-green);
            margin-top: 10px;
        }
        
        .order-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        .btn-order {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-reorder {
            background-color: var(--harvest-orange);
            color: white;
            border: none;
        }
        
        .btn-reorder:hover {
            background-color: #e65100;
            transform: translateY(-2px);
        }
        
        .btn-contact {
            background-color: var(--primary-green);
            color: white;
            border: none;
        }
        
        .btn-contact:hover {
            background-color: var(--dark-green);
            transform: translateY(-2px);
        }
        
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .status-completed {
            background-color: #e8f5e9;
            color: var(--dark-green);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 768px) {
            .order-body {
                flex-direction: column;
            }
            
            .order-image {
                width: 100%;
                height: 200px;
                object-fit: contain;
            }
            
            .order-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            
            .order-actions {
                flex-direction: column;
            }
            
            .btn-order {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">AgriCycle</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="dashboard/buyer_dashboard.php"><i class="bi bi-house-door"></i> Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="marketplace/index.php"><i class="bi bi-cart"></i> Marketplace</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="orders.php"><i class="bi bi-box-seam"></i> My Orders</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-danger" href="auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="orders-container">
    <div class="page-header animate__animated animate__fadeIn">
        <h2><i class="bi bi-box-seam"></i> My Orders</h2>
        <p class="lead text-muted">Your sustainable agricultural purchases</p>
    </div>

    <?php if (empty($orders)): ?>
        <div class="empty-state animate__animated animate__fadeIn">
            <i class="bi bi-cart-x"></i>
            <h3>No Orders Yet</h3>
            <p>You haven't placed any orders yet. Browse our marketplace to find sustainable agricultural products.</p>
            <a href="marketplace/index.php" class="btn btn-success btn-lg"><i class="bi bi-cart"></i> Explore Marketplace</a>
        </div>
    <?php else: ?>
        <div class="order-list">
            <?php foreach ($orders as $order): ?>
                <div class="order-card animate__animated animate__fadeInUp">
                    <div class="order-header">
                        <span class="order-id">Order #<?= $order['order_id'] ?></span>
                        <span class="order-date"><?= date('F j, Y \a\t g:i A', strtotime($order['created_at'])) ?></span>
                    </div>
                    <div class="order-body">
                        <div class="order-image-container">
                            <?php if (!empty($order['photo_path'])): ?>
                                <img src="<?= file_exists($order['photo_path']) ? htmlspecialchars($order['photo_path']) : '../'.htmlspecialchars($order['photo_path']) ?>" 
                                     class="order-image" 
                                     alt="<?= htmlspecialchars($order['item_name']) ?>">
                            <?php else: ?>
                                <img src="../assets/no-image.png" class="order-image" alt="No image available">
                            <?php endif; ?>
                        </div>
                        <div class="order-details">
                            <h3 class="order-title"><?= htmlspecialchars($order['item_name']) ?></h3>
                            <p class="order-description"><?= htmlspecialchars($order['description']) ?></p>
                            
                            <div class="order-meta">
                                <div class="meta-item">
                                    <span class="meta-label">Unit Price</span>
                                    <span class="meta-value">₹<?= $order['price'] ?></span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Quantity</span>
                                    <span class="meta-value"><?= $order['quantity'] ?></span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Status</span>
                                    <span class="status-badge status-completed">
                                        <i class="bi bi-check-circle"></i> Completed
                                    </span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Seller Contact</span>
                                    <span class="meta-value"><?= htmlspecialchars($order['contact_info']) ?></span>
                                </div>
                            </div>
                            
                            <div class="order-price">Total: ₹<?= $order['total_price'] ?></div>
                            
                            <div class="order-actions">
                                <a href="marketplace/index.php?search=<?= urlencode($order['item_name']) ?>" class="btn-order btn-reorder">
                                    <i class="bi bi-arrow-repeat"></i> Reorder
                                </a>
                                <a href="tel:<?= htmlspecialchars($order['contact_info']) ?>" class="btn-order btn-contact">
                                    <i class="bi bi-telephone"></i> Contact Seller
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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