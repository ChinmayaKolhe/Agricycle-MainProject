<?php
session_start();
include '../config/db_connect.php';

if (!isset($_GET['id'])) {
    die("Item not found.");
}

$item_id = intval($_GET['id']);
$query = "SELECT * FROM marketplace_items WHERE id = '$item_id'";
$result = mysqli_query($conn, $query);
$item = mysqli_fetch_assoc($result);

if (!$item) {
    die("Item not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($item['item_name']) ?> | AgriCycle</title>
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
        
        .item-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .item-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            animation: fadeIn 0.5s ease-out;
        }
        
        .item-header {
            text-align: center;
            padding: 30px 20px;
            background: linear-gradient(135deg, var(--light-green), var(--primary-green));
            color: white;
        }
        
        .item-header h2 {
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .item-body {
            display: flex;
            padding: 0;
        }
        
        .item-gallery {
            flex: 1;
            padding: 20px;
            background: #f9f9f9;
        }
        
        .main-image {
            width: 100%;
            height: 400px;
            object-fit: contain;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 15px;
        }
        
        .thumbnail-container {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 10px;
        }
        
        .thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s;
        }
        
        .thumbnail:hover {
            border-color: var(--primary-green);
            transform: translateY(-3px);
        }
        
        .item-details {
            flex: 1;
            padding: 30px;
        }
        
        .detail-group {
            margin-bottom: 20px;
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--earth-brown);
            margin-bottom: 5px;
        }
        
        .detail-value {
            font-size: 1.1rem;
            color: #333;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 5px;
        }
        
        .price-tag {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-green);
            margin: 20px 0;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .btn-action {
            padding: 12px 25px;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-action i {
            font-size: 1.1rem;
        }
        
        .btn-buy {
            background-color: var(--harvest-orange);
            color: white;
            border: none;
        }
        
        .btn-buy:hover {
            background-color: #e65100;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(251, 140, 0, 0.3);
        }
        
        .btn-wishlist {
            background-color: white;
            color: #e53935;
            border: 2px solid #e53935;
        }
        
        .btn-wishlist:hover {
            background-color: #e53935;
            color: white;
            transform: translateY(-3px);
        }
        
        .btn-contact {
            background-color: var(--primary-green);
            color: white;
            border: none;
        }
        
        .btn-contact:hover {
            background-color: var(--dark-green);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(46, 125, 50, 0.3);
        }
        
        .organic-badge {
            background-color: var(--primary-green);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
            margin-left: 15px;
        }
        
        .quantity-indicator {
            display: inline-block;
            padding: 5px 15px;
            background-color: rgba(0,0,0,0.7);
            color: white;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-left: 15px;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 992px) {
            .item-body {
                flex-direction: column;
            }
            
            .main-image {
                height: 300px;
            }
        }
        
        @media (max-width: 576px) {
            .action-buttons {
                flex-direction: column;
            }
            
            .btn-action {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<?php include '../marketplace/navbar.php'; ?>

<div class="item-container">
    <div class="item-card animate__animated animate__fadeIn">
        <div class="item-header">
            <h2><?= htmlspecialchars($item['item_name']) ?> <span class="organic-badge">Organic</span></h2>
            <p>Agricultural Waste Product</p>
        </div>
        
        <div class="item-body">
            <div class="item-gallery">
                <?php if (!empty($item['photo_path'])): ?>
                    <img src="<?= file_exists($item['photo_path']) ? htmlspecialchars($item['photo_path']) : '../'.htmlspecialchars($item['photo_path']) ?>" 
                         class="main-image" 
                         alt="<?= htmlspecialchars($item['item_name']) ?>"
                         id="mainImage">
                <?php else: ?>
                    <img src="../assets/no-image.png" class="main-image" alt="No image available">
                <?php endif; ?>
                
                <div class="thumbnail-container">
                    <?php if (!empty($item['photo_path'])): ?>
                        <img src="<?= file_exists($item['photo_path']) ? htmlspecialchars($item['photo_path']) : '../'.htmlspecialchars($item['photo_path']) ?>" 
                             class="thumbnail" 
                             onclick="document.getElementById('mainImage').src = this.src">
                    <?php endif; ?>
                    <!-- Additional thumbnails can be added here if available -->
                </div>
            </div>
            
            <div class="item-details">
                <div class="price-tag">₹<?= htmlspecialchars($item['price']) ?> <span class="quantity-indicator"><?= htmlspecialchars($item['quantity']) ?> available</span></div>
                
                <div class="detail-group">
                    <div class="detail-label">Description</div>
                    <div class="detail-value"><?= htmlspecialchars($item['description']) ?></div>
                </div>
                
                <div class="detail-group">
                    <div class="detail-label">Seller Contact</div>
                    <div class="detail-value">
                        <i class="bi bi-telephone"></i> <?= htmlspecialchars($item['contact_info']) ?>
                    </div>
                </div>
                
                <div class="detail-group">
                    <div class="detail-label">Product Details</div>
                    <div class="detail-value">
                        <div><i class="bi bi-calendar"></i> Listed on: <?= date('F j, Y', strtotime($item['created_at'])) ?></div>
                        <div><i class="bi bi-geo-alt"></i> Location: Farm Source</div>
                        <div><i class="bi bi-recycle"></i> Sustainable Product</div>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'buyer'): ?>
                        <a href="buy_item.php?id=<?= $item['id'] ?>" class="btn-action btn-buy">
                            <i class="bi bi-cart"></i> Buy Now
                        </a>
                        <a href="add_to_wishlist.php?id=<?= $item['id'] ?>" class="btn-action btn-wishlist">
                            <i class="bi bi-heart"></i> Add to Wishlist
                        </a>
                    <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'farmer' && $_SESSION['user_id'] == $item['user_id']): ?>
                        <a href="edit_item.php?id=<?= $item['id'] ?>" class="btn-action btn-contact">
                            <i class="bi bi-pencil"></i> Edit Item
                        </a>
                        <a href="delete_item.php?id=<?= $item['id'] ?>" class="btn-action btn-wishlist">
                            <i class="bi bi-trash"></i> Delete Item
                        </a>
                    <?php endif; ?>
                    <a href="tel:<?= htmlspecialchars($item['contact_info']) ?>" class="btn-action btn-contact">
                        <i class="bi bi-telephone-outbound"></i> Contact Seller
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Image gallery functionality
    document.addEventListener('DOMContentLoaded', function() {
        const thumbnails = document.querySelectorAll('.thumbnail');
        const mainImage = document.getElementById('mainImage');
        
        if (thumbnails.length > 0 && mainImage) {
            thumbnails.forEach(thumb => {
                thumb.addEventListener('click', function() {
                    // Remove active class from all thumbnails
                    thumbnails.forEach(t => t.style.borderColor = 'transparent');
                    // Add active class to clicked thumbnail
                    this.style.borderColor = 'var(--primary-green)';
                    // Update main image
                    mainImage.src = this.src;
                });
            });
            
            // Activate first thumbnail by default
            thumbnails[0].style.borderColor = 'var(--primary-green)';
        }
        
        // Animation for buttons
        const buttons = document.querySelectorAll('.btn-action');
        buttons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-3px)';
            });
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>
</body>
</html>