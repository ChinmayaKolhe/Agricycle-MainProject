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
        
        .buy-item-container {
            max-width: 800px;
            margin: 2rem auto;
        }
        
        .buy-item-card {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            transition: all 0.3s ease;
            background: white;
        }
        
        .buy-item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        
        .buy-item-header {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            padding: 1.5rem;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .buy-item-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            transform: rotate(30deg);
        }
        
        .buy-item-header h3 {
            position: relative;
            font-weight: 700;
            margin-bottom: 0;
        }
        
        .buy-item-body {
            padding: 2rem;
        }
        
        .item-description {
            background-color: rgba(129, 199, 132, 0.1);
            border-left: 4px solid var(--primary-green);
            padding: 1rem;
            border-radius: 0 8px 8px 0;
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--earth-brown);
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.25rem rgba(46, 125, 50, 0.25);
        }
        
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .quantity-input {
            max-width: 120px;
            text-align: center;
        }
        
        .total-cost-display {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark-green);
            padding: 1rem;
            background-color: rgba(129, 199, 132, 0.1);
            border-radius: 10px;
            text-align: center;
        }
        
        .seller-info {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin: 1.5rem 0;
        }
        
        .btn-confirm {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
        }
        
        .btn-confirm:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(46, 125, 50, 0.4);
            color: white;
            background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
        }
        
        .btn-cancel {
            background: white;
            color: #6c757d;
            border: 1px solid #dee2e6;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-cancel:hover {
            background: #f8f9fa;
            color: #495057;
            transform: translateY(-3px);
        }
        
        .price-tag {
            background-color: var(--sun-yellow);
            color: var(--earth-brown);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 1rem;
        }
        
        .stock-available {
            color: var(--primary-green);
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .buy-item-container {
                padding: 0 1rem;
            }
            
            .buy-item-body {
                padding: 1.5rem;
            }
            
            .quantity-control {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="buy-item-container animate__animated animate__fadeIn">
        <div class="buy-item-card">
            <div class="buy-item-header">
                <h3><i class="bi bi-cart-check"></i> Buy Now</h3>
            </div>
            
            <div class="buy-item-body">
                <h4 class="mb-3"><?= htmlspecialchars($item['item_name']) ?></h4>
                
                <div class="price-tag animate__animated animate__fadeInLeft">
                    ₹<?= htmlspecialchars($item['price']) ?> per kg/unit
                </div>
                
                <div class="item-description animate__animated animate__fadeIn">
                    <p class="mb-0"><?= htmlspecialchars($item['description']) ?></p>
                </div>
                
                <p class="stock-available animate__animated animate__fadeIn">
                    <i class="bi bi-box-seam"></i> Available: <?= $item['quantity'] ?> kg
                </p>
                
                <form action="place_order.php" method="POST" class="animate__animated animate__fadeInUp">
                    <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                    <input type="hidden" id="price" value="<?= $item['price'] ?>">
                    
                    <div class="mb-4">
                        <label for="quantity" class="form-label">Select Quantity (kg)</label>
                        <div class="quantity-control">
                            <input type="number" class="form-control quantity-input" id="quantity" name="quantity"
                                   min="1" max="<?= $item['quantity'] ?>" value="1" required
                                   oninput="updateTotal()">
                            <span>kg</span>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Total Cost</label>
                        <div class="total-cost-display">
                            ₹<span id="total_cost">0.00</span>
                        </div>
                        <input type="hidden" id="total_cost_input" name="total_cost">
                    </div>
                    
                    <div class="seller-info animate__animated animate__fadeIn">
                        <h5><i class="bi bi-person-badge"></i> Seller Information</h5>
                        <p class="mb-0"><?= htmlspecialchars($item['contact_info']) ?></p>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="marketplace.php" class="btn btn-cancel">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-confirm">
                            <i class="bi bi-check-circle"></i> Confirm Purchase
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let debounceTimer;

        function updateTotal() {
            clearTimeout(debounceTimer);

            debounceTimer = setTimeout(() => {
                const price = parseFloat(document.getElementById('price').value);
                const quantityInput = document.getElementById('quantity');
                const totalDisplay = document.getElementById('total_cost');
                const totalInput = document.getElementById('total_cost_input');
                const maxQuantity = parseInt(quantityInput.getAttribute('max'));
                let quantity = parseInt(quantityInput.value);

                if (isNaN(quantity)) {
                    totalDisplay.textContent = "0.00";
                    totalInput.value = "0.00";
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
                const total = (price * quantity).toFixed(2);
                totalDisplay.textContent = total;
                totalInput.value = total;
            }, 400);
        }

        // Initialize total on page load
        window.addEventListener("DOMContentLoaded", updateTotal);
    </script>
</body>
</html>