<?php
session_start();
include '../config/db_connect.php'; // Database connection

// Fetch listings from the database (Prevent SQL Injection using Prepared Statements)
$query = "SELECT * FROM marketplace_items ORDER BY created_at DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
include '../farmer/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace - AgriCycle</title>
    <link rel="stylesheet" href="../assets/css/marketplace.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Roboto+Slab:wght@400;600&display=swap" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        :root {
            --primary-green: #4CAF50;
            --dark-green: #388E3C;
            --light-green: #C8E6C9;
            --earth-brown: #8D6E63;
            --sun-yellow: #FFC107;
            --sky-blue: #03A9F4;
            --white: #FFFFFF;
            --light-gray: #F5F5F5;
            --text-dark: #333333;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-gray);
            color: var(--text-dark);
            background-image: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            background-blend-mode: overlay;
            background-color: rgba(255, 255, 255, 0.9);
        }
        
        .container-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        h2, h3 {
            font-family: 'Roboto Slab', serif;
            color: var(--dark-green);
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.1);
        }
        
        h2 {
            font-size: 2.5rem;
            margin-top: 20px;
            position: relative;
            padding-bottom: 15px;
            animation: fadeInDown 1s;
        }
        
        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-green), var(--sun-yellow));
            border-radius: 3px;
        }
        
        .form-container {
            background-color: var(--white);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-left: 5px solid var(--primary-green);
            transition: transform 0.3s, box-shadow 0.3s;
            animation: fadeIn 0.8s;
        }
        
        .form-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-green);
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
            background-color: var(--light-gray);
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
            outline: none;
            background-color: var(--white);
        }
        
        button[type="submit"] {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
            display: block;
            width: 100%;
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 10px rgba(0,0,0,0.15);
            background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
        }
        
        .listing-container {
            background-color: var(--white);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            animation: fadeIn 1s;
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            animation: fadeInUp 0.8s;
        }
        
        th {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 500;
        }
        
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        
        tr:nth-child(even) {
            background-color: var(--light-gray);
        }
        
        tr:hover {
            background-color: var(--light-green);
            transition: background-color 0.2s;
        }
        
        .edit-btn, .delete-btn {
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            margin-right: 5px;
            transition: all 0.2s;
            display: inline-block;
        }
        
        .edit-btn {
            background-color: var(--sky-blue);
            color: white;
        }
        
        .edit-btn:hover {
            background-color: #0288D1;
            transform: translateY(-1px);
        }
        
        .delete-btn {
            background-color: #F44336;
            color: white;
        }
        
        .delete-btn:hover {
            background-color: #D32F2F;
            transform: translateY(-1px);
        }
        
        a {
            color: var(--primary-green);
            text-decoration: none;
            transition: color 0.2s;
        }
        
        a:hover {
            color: var(--dark-green);
            text-decoration: underline;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container-wrapper {
                padding: 15px;
            }
            
            h2 {
                font-size: 2rem;
            }
            
            .form-container, .listing-container {
                padding: 20px;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
        }
        
        /* Animation for table rows */
        @keyframes fadeInRow {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        tr {
            animation: fadeInRow 0.5s ease-out forwards;
            animation-delay: calc(var(--row-index) * 0.1s);
        }
        
        /* Floating animation for featured items */
        .featured-item {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0px); }
        }
        
        /* Pulse animation for new listings */
        .new-listing {
            position: relative;
        }
        
        .new-listing::after {
            content: 'New';
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: var(--sun-yellow);
            color: var(--text-dark);
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: bold;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="container-wrapper">
        <h2 class="animate__animated animate__fadeInDown">Farm Waste & Recyclable Items Marketplace</h2>
        
        <div class="form-container animate__animated animate__fadeIn">
            <h3><i class="bi bi-plus-circle-fill"></i> List a New Item</h3>
            <form action="add_marketplace_item.php" method="POST">
                <div class="form-group">
                    <label><i class="bi bi-tag-fill"></i> Item Name</label>
                    <input type="text" name="item_name" required placeholder="e.g. Organic Banana Peels">
                </div>
                
                <div class="form-group">
                    <label><i class="bi bi-card-text"></i> Item Description</label>
                    <textarea name="description" rows="3" required placeholder="Describe your item in detail..."></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="bi bi-currency-rupee"></i> Price per kg/unit (₹)</label>
                            <input type="number" name="price" required placeholder="0.00" step="0.01">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="bi bi-box-seam"></i> Quantity Available (kg)</label>
                            <input type="number" name="quantity" required placeholder="0">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="bi bi-telephone-fill"></i> Contact Info (Phone/Email)</label>
                    <input type="text" name="contact_info" required placeholder="Phone number or email address">
                </div>
                
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-circle-fill"></i> Add Listing
                </button>
            </form>
        </div>

        <div class="listing-container">
            <h3><i class="bi bi-list-ul"></i> Available Listings</h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><i class="bi bi-tag"></i> Item Name</th>
                            <th><i class="bi bi-card-text"></i> Description</th>
                            <th><i class="bi bi-currency-rupee"></i> Price</th>
                            <th><i class="bi bi-box"></i> Quantity</th>
                            <th><i class="bi bi-person-lines-fill"></i> Contact</th>
                            <th><i class="bi bi-gear"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $rowIndex = 0;
                        while ($row = mysqli_fetch_assoc($result)) { 
                            $contact = htmlspecialchars($row['contact_info']);
                            $id = $row['id'];
                            $isNew = (strtotime($row['created_at']) > strtotime('-3 days'));
                            
                            if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
                                $contact_link = "<a href='mailto:$contact'><i class='bi bi-envelope-fill'></i> $contact</a>";
                            } elseif (preg_match('/^\+?\d{10,}$/', $contact)) {
                                $contact_link = "<a href='tel:$contact'><i class='bi bi-telephone-fill'></i> $contact</a>";
                            } else {
                                $contact_link = $contact;
                            }
                            
                            $rowClass = $isNew ? 'new-listing' : '';
                        ?>
                        <tr class="<?= $rowClass ?>" style="--row-index: <?= $rowIndex++ ?>;">
                            <td><?= htmlspecialchars($row['item_name']) ?></td>
                            <td><?= htmlspecialchars($row['description']) ?></td>
                            <td>₹<?= htmlspecialchars($row['price']) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?> kg</td>
                            <td><?= $contact_link ?></td>
                            <td>
                                <a href="edit_marketplace_item.php?id=<?= $id ?>" class="edit-btn"><i class="bi bi-pencil-square"></i> Edit</a>
                                <a href="delete_marketplace_item.php?id=<?= $id ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this listing?')"><i class="bi bi-trash-fill"></i> Delete</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Add animation to form elements when they come into view
        document.addEventListener('DOMContentLoaded', function() {
            const formGroups = document.querySelectorAll('.form-group');
            
            formGroups.forEach((group, index) => {
                group.style.opacity = '0';
                group.style.transform = 'translateY(20px)';
                group.style.transition = `all 0.5s ease ${index * 0.1}s`;
                
                setTimeout(() => {
                    group.style.opacity = '1';
                    group.style.transform = 'translateY(0)';
                }, 100);
            });
            
            // Highlight new listings for better visibility
            const newListings = document.querySelectorAll('.new-listing');
            newListings.forEach(listing => {
                listing.style.borderLeft = '3px solid var(--sun-yellow)';
            });
        });
    </script>
</body>
</html>