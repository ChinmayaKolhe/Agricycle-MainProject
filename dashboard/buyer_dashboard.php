<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer') {
    header("Location: ../auth/login.php");
    exit();
}
if (!$buyer['is_verified']) {
    
    header("Location: ../auth/verify_aadhaar_buyer.php");
    exit();
}

include '../config/db_connect.php';
include '../buyer/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buyer Dashboard | AgriCycle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- CSS Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<div class="container mt-4">
    <h2 class="text-primary">Welcome, Buyer!</h2>
    <p class="text-muted">Explore waste listings, connect with farmers, and contribute to sustainability.</p>

    <!-- Dashboard Cards -->
    <div class="row g-4">
        <!-- Marketplace -->
        <div class="col-md-3">
            <div class="card shadow-lg border-0 text-center">
                <div class="card-body">
                    <i class="bi bi-cart display-4 text-primary"></i>
                    <h5 class="mt-3">Marketplace</h5>
                    <p>Browse and purchase waste materials.</p>
                    <a href="../marketplace/index.php" class="btn btn-outline-primary">Go to Marketplace</a>
                </div>
            </div>
        </div>

        <!-- Wishlist -->
        <div class="col-md-3">
            <div class="card shadow-lg border-0 text-center">
                <div class="card-body">
                    <i class="bi bi-heart display-4 text-danger"></i>
                    <h5 class="mt-3">Wishlist</h5>
                    <p>Save items for later purchase.</p>
                    <a href="../wishlist.php" class="btn btn-outline-danger">View Wishlist</a>
                </div>
            </div>
        </div>

        <!-- Orders -->
        <div class="col-md-3">
            <div class="card shadow-lg border-0 text-center">
                <div class="card-body">
                    <i class="bi bi-envelope-check display-4 text-success"></i>
                    <h5 class="mt-3">Order History</h5>
                    <p>Track your previous purchases.</p>
                    <a href="../orders.php" class="btn btn-outline-success">View Orders</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-lg border-0 text-center">
                <div class="card-body">
                    <i class="bi bi-envelope-check display-4 text-success"></i>
                    <h5 class="mt-3">Community Forum</h5>
                    <p>Join the AgriCycle community.</p>
                    <a href="../community/community_forum.php" class="btn btn-outline-info">Visit Forum</a>
                </div>
            </div>
        </div>


    </div>

    <!-- Insights -->
    <div class="row mt-4">
        <!-- Purchase Stats -->
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <h5>Purchase Statistics</h5>
                    <canvas id="buyerChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recommendations -->
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <h5>Personalized Recommendations</h5>
                    <p>Suggested waste materials based on your purchases.</p>
                    <a href="../recommendations.php" class="btn btn-outline-info">View Recommendations</a>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Buyer Purchase Chart
const ctx = document.getElementById('buyerChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Plastic', 'Metal', 'E-Waste', 'Organic'],
        datasets: [{
            label: 'Purchases',
            data: [30, 20, 15, 35],
            backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545']
        }]
    },
    options: {
        responsive: true
    }
});


</script>

</body>
</html>
