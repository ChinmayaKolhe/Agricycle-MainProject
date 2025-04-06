<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'buyer') {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit();
}

$item_id = mysqli_real_escape_string($conn, $_GET['id']);

$query = "SELECT * FROM marketplace_items WHERE id = '$item_id'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Item not found.";
    exit();
}

$item = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Now - <?= htmlspecialchars($item['item_name']) ?> | AgriCycle</title>
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
        
        .purchase-container {
            max-width: 800px;
            margin: 40px auto;
            animation: fadeIn 0.5s ease-out;
        }
        
        .purchase-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
        }
        
        .purchase-header {
            background: linear-gradient(135deg, var(--light-green), var(--primary-green));
            color: white;
            padding: 25px;
            text-align: center;
        }
        
        .purchase-header h3 {
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .purchase-body {
            padding: 30px;
        }
        
        .item-image {
            width: 100%;
            height: 200px;
            object-fit: contain;
            border-radius: 10px;
            margin-bottom: 20px;
            background: #f9f9f9;
            padding: 10px;
        }
        
        .item-description {
            color: #555;
            font-size: 1rem;
            margin-bottom: 25px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 8px;
            border-left: 4px solid var(--primary-green);
        }
        
        .form-label {
            font-weight: 600;
            color: var(--earth-brown);
            margin-bottom: 8px;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.25rem rgba(46, 125, 50, 0.25);
        }
        
        .total-display {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-green);
            margin: 20px 0;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 8px;
            text-align: center;
        }
        
        .seller-info {
            padding: 15px;
            background: #f5f5f5;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid var(--harvest-orange);
        }
        
        .seller-info strong {
            color: var(--earth-brown);
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 25px;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-success {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
        }
        
        .btn-success:hover {
            background-color: var(--dark-green);
            border-color: var(--dark-green);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(46, 125, 50, 0.3);
        }
        
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
            transform: translateY(-3px);
        }
        
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .quantity-control button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--light-green);
            color: white;
            border: none;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .quantity-control button:hover {
            background-color: var(--primary-green);
            transform: scale(1.1);
        }
        
        .quantity-control input {
            text-align: center;
            max-width: 80px;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 576px) {
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<?php include '../marketplace/navbar.php'; ?>

<div class="purchase-container animate__animated animate__fadeIn">
    <div class="purchase-card">
        <div class="purchase-header">
            <h3><i class="bi bi-cart-check"></i> Purchase - <?= htmlspecialchars($item['item_name']) ?></h3>
            <p>Complete your sustainable agricultural purchase</p>
        </div>
        
        <div class="purchase-body">
            <?php if (!empty($item['photo_path'])): ?>
                <img src="<?= file_exists($item['photo_path']) ? htmlspecialchars($item['photo_path']) : '../'.htmlspecialchars($item['photo_path']) ?>" 
                     class="item-image" 
                     alt="<?= htmlspecialchars($item['item_name']) ?>">
            <?php else: ?>
                <img src="../assets/no-image.png" class="item-image" alt="No image available">
            <?php endif; ?>
            
            <div class="item-description">
                <?= htmlspecialchars($item['description']) ?>
            </div>
            
            <form action="place_order.php" method="POST">
                <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                <input type="hidden" id="price" value="<?= $item['price'] ?>">
                <input type="hidden" id="max_quantity" value="<?= $item['quantity'] ?>">
                
                <div class="mb-4">
                    <label for="quantity" class="form-label">Select Quantity</label>
                    <div class="quantity-control">
                        <button type="button" onclick="adjustQuantity(-1)">-</button>
                        <input type="number" class="form-control" id="quantity" name="quantity"
                               min="1" max="<?= $item['quantity'] ?>" value="1" required
                               oninput="updateTotal()">
                        <button type="button" onclick="adjustQuantity(1)">+</button>
                    </div>
                    <div class="form-text">Available: <?= $item['quantity'] ?> units</div>
                </div>
                
                <div class="total-display">
                    Total Cost: ₹<span id="total_cost">0.00</span>
                </div>
                
                <div class="seller-info">
                    <strong><i class="bi bi-person-circle"></i> Seller Contact:</strong><br>
                    <?= htmlspecialchars($item['contact_info']) ?>
                </div>
                
                <div class="action-buttons">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Confirm Purchase
                    </button>
                    <a href="marketplace.php" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let debounceTimer;

    function updateTotal() {
        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(() => {
            const price = parseFloat(document.getElementById('price').value);
            const quantityInput = document.getElementById('quantity');
            const totalField = document.getElementById('total_cost');
            const maxQuantity = parseInt(quantityInput.getAttribute('max'));
            let quantity = parseInt(quantityInput.value);

            if (isNaN(quantity)) {
                totalField.textContent = "0.00";
                return;
            }

            if (quantity < 1) {
                quantity = 1;
            }

            if (quantity > maxQuantity) {
                alert("Selected quantity exceeds available stock!");
                quantity = maxQuantity;
            }

            quantityInput.value = quantity;
            totalField.textContent = (price * quantity).toFixed(2);
        }, 400);
    }

    function adjustQuantity(change) {
        const quantityInput = document.getElementById('quantity');
        const maxQuantity = parseInt(document.getElementById('max_quantity').value);
        let quantity = parseInt(quantityInput.value) || 0;
        
        quantity += change;
        
        if (quantity < 1) quantity = 1;
        if (quantity > maxQuantity) {
            alert("Selected quantity exceeds available stock!");
            quantity = maxQuantity;
        }
        
        quantityInput.value = quantity;
        updateTotal();
    }

    window.addEventListener("DOMContentLoaded", function() {
        updateTotal();
        
        // Animation for buttons
        const buttons = document.querySelectorAll('.btn');
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