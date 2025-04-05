<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer') {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/db_connect.php';
include '../farmer/header.php';

$farmer_id = $_SESSION['user_id'];

// Fetch unread notifications for the farmer
$notifications_query = "SELECT * FROM notifications WHERE user_id = '$farmer_id' AND is_read = FALSE ORDER BY created_at DESC";
$result = mysqli_query($conn, $notifications_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Dashboard | AgriCycle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
</head>
<body>

<div class="container mt-4">
    <h2 class="text-success">Welcome, Farmer!</h2>
    <p class="text-muted">Manage your waste, explore the marketplace, and connect with the community.</p>

 

    <!-- Dashboard Cards -->
    <div class="row g-4 mt-4">
        <div class="col-md-4">
            <div class="card shadow-lg border-0">
                <div class="card-body text-center">
                    <i class="bi bi-recycle display-4 text-success"></i>
                    <h5 class="card-title mt-3">Waste Listings</h5>
                    <p class="card-text">View and manage your waste listings.</p>
                    <a href="../farmer/waste_requests.php" class="btn btn-outline-success">Go to Waste Requests</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-lg border-0">
                <div class="card-body text-center">
                    <i class="bi bi-truck display-4 text-primary"></i>
                    <h5 class="card-title mt-3">Pickup Requests</h5>
                    <p class="card-text">Schedule and track waste pickup services.</p>
                    <a href="../farmer/pickup_requests.php" class="btn btn-outline-primary">Manage Pickups</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-lg border-0">
                <div class="card-body text-center">
                    <i class="bi bi-people-fill display-4 text-warning"></i>
                    <h5 class="card-title mt-3">Community Forum</h5>
                    <p class="card-text">Connect with other farmers and buyers.</p>
                    <a href="../community/community_forum.php" class="btn btn-outline-warning">Join Community</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Waste Statistics -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <h5 class="card-title">Waste Statistics</h5>
                    <canvas id="wasteChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <h5 class="card-title">Government Schemes</h5>
                    <p>Check latest subsidies and policies.</p>
                    <a href="../farmer/govt_schemes.php" class="btn btn-outline-info">View Schemes</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('wasteChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Plastic', 'Organic', 'Metal', 'E-Waste'],
        datasets: [{
            label: 'Waste in Kg',
            data: [12, 19, 7, 5],
            backgroundColor: ['#28a745', '#ffc107', '#007bff', '#dc3545']
        }]
    },
    options: {
        responsive: true
    }
});

// Mark notifications as read using AJAX
document.getElementById('markAllRead').addEventListener('click', function() {
    fetch('mark_notifications_read.php', { method: 'POST' })
    .then(response => response.text())
    .then(data => {
        document.getElementById('notificationList').innerHTML = '<p>All notifications marked as read.</p>';
    });
});
</script>

</body>
</html>
