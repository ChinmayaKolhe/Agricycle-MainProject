<?php
session_start();
include 'config/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Please login to view your wishlist.");
}

$user_id = $_SESSION['user_id'];

// Fetch wishlist items with full product details
$query = "SELECT mi.id, mi.item_name, mi.description, mi.price, mi.quantity, mi.contact_info, mi.photo_path
          FROM wishlist 
          JOIN marketplace_items mi ON wishlist.item_id = mi.id
          WHERE wishlist.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$items = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Wishlist | AgriCycle</title>
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
        
        .wishlist-container {
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
        
        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }
        
        .wishlist-card {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .wishlist-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.12);
        }
        
        .card-img-container {
            height: 200px;
            overflow: hidden;
            position: relative;
        }
        
        .card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .wishlist-card:hover .card-img {
            transform: scale(1.05);
        }
        
        .wishlist-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: var(--primary-green);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 1;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .card-body {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .card-title {
            color: var(--dark-green);
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .card-description {
            color: #555;
            font-size: 0.95rem;
            margin-bottom: 15px;
            flex: 1;
        }
        
        .card-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-green);
            margin: 10px 0;
        }
        
        .card-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .card-meta-item {
            display: flex;
            align-items: center;
            color: #666;
            font-size: 0.9rem;
        }
        
        .card-meta-item i {
            margin-right: 5px;
            color: var(--primary-green);
        }
        
        .card-actions {
            display: flex;
            gap: 10px;
            margin-top: auto;
        }
        
        .btn-action {
            flex: 1;
            border-radius: 25px;
            padding: 8px 15px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
        
        .btn-remove {
            background-color: #e53935;
            color: white;
            border: none;
        }
        
        .btn-remove:hover {
            background-color: #c62828;
            transform: translateY(-2px);
        }
        
        .btn-buy {
            background-color: var(--harvest-orange);
            color: white;
            border: none;
        }
        
        .btn-buy:hover {
            background-color: #e65100;
            transform: translateY(-2px);
        }
        
        .btn-view {
            background-color: var(--primary-green);
            color: white;
            border: none;
        }
        
        .btn-view:hover {
            background-color: var(--dark-green);
            transform: translateY(-2px);
        }
        
        .animate-delay-1 {
            animation-delay: 0.1s;
        }
        
        .animate-delay-2 {
            animation-delay: 0.2s;
        }
        
        @media (max-width: 768px) {
            .wishlist-grid {
                grid-template-columns: 1fr;
            }
            
            .card-actions {
                flex-direction: column;
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
          <a class="nav-link text-danger" href="auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="wishlist-container">
    <div class="page-header animate__animated animate__fadeIn">
        <h2><i class="bi bi-heart-fill"></i> Your Wishlist</h2>
        <p class="lead text-muted">Your saved agricultural products and sustainable items</p>
    </div>

    <?php if (empty($items)): ?>
        <div class="empty-state animate__animated animate__fadeIn">
            <i class="bi bi-heart"></i>
            <h3>Your Wishlist is Empty</h3>
            <p>You haven't saved any items yet. Browse our marketplace to find sustainable agricultural products you love.</p>
            <a href="marketplace/index.php" class="btn btn-success btn-lg"><i class="bi bi-cart"></i> Explore Marketplace</a>
        </div>
    <?php else: ?>
        <div class="wishlist-grid">
            <?php foreach ($items as $index => $item): ?>
                <div class="wishlist-card animate__animated animate__fadeInUp animate-delay-<?= $index % 2 ?> floating-card">
                    <div class="card-img-container">
                        <?php if (!empty($item['photo_path']) && file_exists($item['photo_path'])): ?>
                            <img src="<?= htmlspecialchars($item['photo_path']) ?>" class="card-img" alt="<?= htmlspecialchars($item['item_name']) ?>">
                        <?php elseif (!empty($item['photo_path'])): ?>
                            <!-- Try with different path structures if first attempt fails -->
                            <img src="../<?= htmlspecialchars($item['photo_path']) ?>" class="card-img" alt="<?= htmlspecialchars($item['item_name']) ?>">
                        <?php else: ?>
                            <img src="../assets/no-image.png" class="card-img" alt="No image available">
                        <?php endif; ?>
                        <span class="wishlist-badge">Saved</span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($item['item_name']) ?></h5>
                        <p class="card-description"><?= htmlspecialchars($item['description']) ?></p>
                        <div class="card-price">₹<?= htmlspecialchars($item['price']) ?></div>
                        <div class="card-meta">
                            <span class="card-meta-item"><i class="bi bi-box-seam"></i> <?= htmlspecialchars($item['quantity']) ?> available</span>
                            <span class="card-meta-item"><i class="bi bi-telephone"></i> <?= htmlspecialchars($item['contact_info']) ?></span>
                        </div>
                        <div class="card-actions">
                            <a href="remove_from_wishlist.php?id=<?= $item['id'] ?>" class="btn-action btn-remove">
                                <i class="bi bi-trash"></i> Remove
                            </a>
                            <a href="marketplace/view_item.php?id=<?= $item['id'] ?>" class="btn-action btn-view">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="marketplace/buy_item.php?id=<?= $item['id'] ?>" class="btn-action btn-buy">
                                <i class="bi bi-cart"></i> Buy
                            </a>
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
        
        // Debugging for image paths
        <?php if (!empty($items)): ?>
            console.log("Image paths debugging:");
            <?php foreach ($items as $item): ?>
                console.log({
                    originalPath: "<?= $item['photo_path'] ?>",
                    exists: <?= !empty($item['photo_path']) && file_exists($item['photo_path']) ? 'true' : 'false' ?>,
                    altPathExists: <?= !empty($item['photo_path']) && file_exists('../'.$item['photo_path']) ? 'true' : 'false' ?>
                });
            <?php endforeach; ?>
        <?php endif; ?>
    });
</script>
</body>
</html>