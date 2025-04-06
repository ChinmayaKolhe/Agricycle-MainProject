<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'buyer') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = $_POST['item_id'];
    $quantity = (int)$_POST['quantity'];
    $total_cost = $_POST['total_cost'];
    $buyer_id = $_SESSION['user_id'];

    // Fetch item
    $query = "SELECT * FROM marketplace_items WHERE id = '$item_id'";
    $result = mysqli_query($conn, $query);
    $item = mysqli_fetch_assoc($result);

    if (!$item) {
        die("Item not found.");
    }

    if ($quantity > $item['quantity']) {
        echo "<script>alert('You cannot buy more than the available quantity.'); window.location.href='buy_item.php?id=$item_id';</script>";
        exit();
    }

    // Update stock
    $new_quantity = $item['quantity'] - $quantity;
    mysqli_query($conn, "UPDATE marketplace_items SET quantity = $new_quantity WHERE id = $item_id");

    // Insert into orders table
    $insert_query = "INSERT INTO orders (buyer_id, item_id, quantity, total_price)
                     VALUES ('$buyer_id', '$item_id', '$quantity', '$total_cost')";
    mysqli_query($conn, $insert_query);

    // Fetch farmer email using user_id from marketplace_items
    $farmer_id = $item['user_id'];
    $farmer_query = "SELECT email FROM farmers WHERE id = '$farmer_id'";
    $farmer_result = mysqli_query($conn, $farmer_query);
    $farmer = mysqli_fetch_assoc($farmer_result);

    if ($farmer) {
        $farmer_email = $farmer['email'];
        $item_name = $item['item_name'];
        $message = "Your item '<b>$item_name</b>' has been purchased. Quantity: $quantity. Total: ₹$total_cost.";

        
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation | AgriCycle</title>
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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .order-confirmation-container {
            max-width: 800px;
            margin: 2rem auto;
            flex: 1;
        }
        
        .confirmation-card {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            transition: all 0.3s ease;
            background: white;
        }
        
        .confirmation-header {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            padding: 2rem;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .confirmation-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            transform: rotate(30deg);
        }
        
        .confirmation-header h2 {
            position: relative;
            font-weight: 700;
            margin-bottom: 0;
        }
        
        .confirmation-header i {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: inline-block;
        }
        
        .confirmation-body {
            padding: 2.5rem;
        }
        
        .invoice-card {
            border-radius: 15px;
            border: 1px solid rgba(0,0,0,0.1);
            background: white;
            padding: 2rem;
            margin: 1.5rem 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .invoice-title {
            color: var(--primary-green);
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 1.5rem;
        }
        
        .invoice-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px dashed #eee;
        }
        
        .invoice-item:last-child {
            border-bottom: none;
        }
        
        .invoice-label {
            font-weight: 600;
            color: var(--earth-brown);
        }
        
        .invoice-value {
            font-weight: 500;
        }
        
        .total-amount {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark-green);
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 2px solid var(--light-green);
        }
        
        .btn-home {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
            text-decoration: none;
            display: inline-block;
            margin-top: 1.5rem;
        }
        
        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(46, 125, 50, 0.4);
            color: white;
            background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
        }
        
        .success-icon {
            font-size: 5rem;
            color: var(--primary-green);
            margin-bottom: 1.5rem;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-20px);}
            60% {transform: translateY(-10px);}
        }
        
        @media (max-width: 768px) {
            .order-confirmation-container {
                padding: 0 1rem;
            }
            
            .confirmation-body {
                padding: 1.5rem;
            }
            
            .invoice-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="order-confirmation-container animate__animated animate__fadeIn">
        <div class="confirmation-card">
            <div class="confirmation-header">
                <i class="bi bi-check-circle-fill"></i>
                <h2>Order Confirmed!</h2>
            </div>
            
            <div class="confirmation-body text-center">
                <div class="success-icon animate__animated animate__bounceIn">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h3 class="text-success mb-4">Thank you for your purchase!</h3>
                <p class="lead">Your order has been successfully placed. Here's your invoice:</p>
                
                <div class="invoice-card animate__animated animate__fadeInUp">
                    <h4 class="invoice-title">Purchase Invoice</h4>
                    
                    <div class="invoice-item">
                        <span class="invoice-label">Item:</span>
                        <span class="invoice-value"><?= htmlspecialchars($item['item_name']) ?></span>
                    </div>
                    
                    <div class="invoice-item">
                        <span class="invoice-label">Description:</span>
                        <span class="invoice-value"><?= htmlspecialchars($item['description']) ?></span>
                    </div>
                    
                    <div class="invoice-item">
                        <span class="invoice-label">Unit Price:</span>
                        <span class="invoice-value">₹<?= htmlspecialchars($item['price']) ?></span>
                    </div>
                    
                    <div class="invoice-item">
                        <span class="invoice-label">Quantity:</span>
                        <span class="invoice-value"><?= htmlspecialchars($quantity) ?></span>
                    </div>
                    
                    <div class="invoice-item">
                        <span class="invoice-label">Seller Contact:</span>
                        <span class="invoice-value"><?= htmlspecialchars($item['contact_info']) ?></span>
                    </div>
                    
                    <div class="invoice-item">
                        <span class="invoice-label">Order Date:</span>
                        <span class="invoice-value"><?= date("F j, Y, g:i a") ?></span>
                    </div>
                    
                    <div class="text-end total-amount">
                        <span>Total Cost: ₹<?= htmlspecialchars($total_cost) ?></span>
                    </div>
                </div>
                
                <p class="text-muted mt-4">The seller has been notified and will contact you shortly.</p>
                <a href="../dashboard/buyer_dashboard.php" class="btn-home">
                    <i class="bi bi-house-door"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>