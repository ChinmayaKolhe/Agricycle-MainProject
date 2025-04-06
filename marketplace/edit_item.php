<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'farmer') {
    die("Access Denied.");
}

if (!isset($_GET['id'])) {
    die("Item not found.");
}

$item_id = intval($_GET['id']);
$seller_id = $_SESSION['user_id'];

$query = "SELECT * FROM marketplace_items WHERE id = '$item_id' AND user_id = '$seller_id'";
$result = mysqli_query($conn, $query);
$item = mysqli_fetch_assoc($result);

if (!$item) {
    die("Item not found or you don't have permission.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $contact_info = mysqli_real_escape_string($conn, $_POST['contact_info']);

    $update_query = "UPDATE marketplace_items SET 
        item_name = '$item_name', 
        description = '$description', 
        price = '$price', 
        quantity = '$quantity', 
        contact_info = '$contact_info' 
        WHERE id = '$item_id' AND user_id = '$seller_id'";

    if (mysqli_query($conn, $update_query)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating item: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item | AgriCycle</title>
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
        
        .edit-item-container {
            max-width: 700px;
            margin: 2rem auto;
        }
        
        .edit-item-card {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            transition: all 0.3s ease;
        }
        
        .edit-item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        
        .edit-item-header {
            background: linear-gradient(135deg, var(--sun-yellow), var(--harvest-orange));
            padding: 2rem;
            text-align: center;
            color: #333;
            position: relative;
            overflow: hidden;
        }
        
        .edit-item-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 70%);
            transform: rotate(30deg);
        }
        
        .edit-item-header h2 {
            position: relative;
            font-weight: 700;
            margin-bottom: 0;
        }
        
        .edit-item-header i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: inline-block;
        }
        
        .edit-item-body {
            padding: 2.5rem;
            background: white;
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
            margin-bottom: 1.5rem;
        }
        
        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.25rem rgba(46, 125, 50, 0.25);
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        .btn-update {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
        }
        
        .btn-update:hover {
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
        
        .price-input {
            position: relative;
        }
        
        .price-input::before {
            content: '₹';
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-weight: 600;
            color: var(--earth-brown);
            z-index: 2;
        }
        
        .price-input input {
            padding-left: 30px;
        }
        
        @media (max-width: 768px) {
            .edit-item-container {
                padding: 0 1rem;
            }
            
            .edit-item-body {
                padding: 1.5rem;
            }
        }
        
        /* Animation for form elements */
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
        
        .animate-form-item {
            animation: fadeInUp 0.6s ease forwards;
        }
    </style>
</head>
<body>
<?php include '../marketplace/navbar.php'; ?>

<div class="edit-item-container animate__animated animate__fadeIn">
    <div class="edit-item-card">
        <div class="edit-item-header">
            <i class="bi bi-pencil-square"></i>
            <h2>Edit Your Listing</h2>
        </div>
        
        <div class="edit-item-body">
            <form method="POST">
                <!-- Item Name -->
                <div class="animate-form-item" style="animation-delay: 0.1s">
                    <label for="item_name" class="form-label">Item Name</label>
                    <input type="text" id="item_name" name="item_name" class="form-control" 
                           value="<?= htmlspecialchars($item['item_name']) ?>" required>
                </div>

                <!-- Description -->
                <div class="animate-form-item" style="animation-delay: 0.2s">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-control" 
                              required><?= htmlspecialchars($item['description']) ?></textarea>
                </div>

                <!-- Price -->
                <div class="animate-form-item" style="animation-delay: 0.3s">
                    <label for="price" class="form-label">Price (₹ per kg/unit)</label>
                    <div class="price-input">
                        <input type="number" id="price" name="price" class="form-control" 
                               value="<?= htmlspecialchars($item['price']) ?>" step="0.01" min="0" required>
                    </div>
                </div>

                <!-- Quantity -->
                <div class="animate-form-item" style="animation-delay: 0.4s">
                    <label for="quantity" class="form-label">Quantity Available (kg)</label>
                    <input type="number" id="quantity" name="quantity" class="form-control" 
                           value="<?= htmlspecialchars($item['quantity']) ?>" min="1" required>
                </div>

                <!-- Contact Info -->
                <div class="animate-form-item" style="animation-delay: 0.5s">
                    <label for="contact_info" class="form-label">Contact Information</label>
                    <input type="text" id="contact_info" name="contact_info" class="form-control" 
                           value="<?= htmlspecialchars($item['contact_info']) ?>" required>
                </div>

                <!-- Form Buttons -->
                <div class="d-flex justify-content-between mt-5">
                    <a href="index.php" class="btn btn-cancel animate__animated animate__fadeInLeft">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-update animate__animated animate__fadeInRight">
                        <i class="bi bi-check-circle"></i> Update Listing
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Add animation to form elements when they come into view
    document.addEventListener('DOMContentLoaded', function() {
        const animateItems = document.querySelectorAll('.animate-form-item');
        
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