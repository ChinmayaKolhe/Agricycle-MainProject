<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer') {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/db_connect.php';
include '../farmer/header.php';

$farmer_id = $_SESSION['user_id'];

// Fetch unread notifications
$notif_query = "SELECT * FROM notifications WHERE user_id = '$farmer_id' AND is_read = 0 ORDER BY created_at DESC";
$result = mysqli_query($conn, $notif_query);

// Waste chart data
$waste_data = [
    'Crop Residue' => 0,
    'Animal Manure' => 0,
    'Fruit and Vegetable Waste' => 0,
    'Agrochemical Containers' => 0,
    'Plastic Mulch' => 0,
    'Spoiled Grain' => 0,
    'Weeds & Grass' => 0
];

$chart_query = "SELECT waste_type, SUM(quantity) as total_quantity FROM waste_listings WHERE farmer_id = $farmer_id GROUP BY waste_type";
$chart_result = mysqli_query($conn, $chart_query);
if ($chart_result && mysqli_num_rows($chart_result) > 0) {
    while ($row = mysqli_fetch_assoc($chart_result)) {
        $type = $row['waste_type'];
        if (array_key_exists($type, $waste_data)) {
            $waste_data[$type] = (int)$row['total_quantity'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Farmer Dashboard | AgriCycle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="text-success">Welcome, Farmer!</h2>
    <p class="text-muted">Manage your waste, explore the marketplace, and connect with the community.</p>


    <!-- Dashboard Cards -->
    <div class="row g-4 mt-2">
        <div class="col-md-4">
            <div class="card shadow-lg border-0 text-center">
                <div class="card-body">
                    <i class="bi bi-recycle display-4 text-success"></i>
                    <h5 class="card-title mt-3">Waste Listings</h5>
                    <a href="../farmer/waste_requests.php" class="btn btn-outline-success">Go to Waste Requests</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-lg border-0 text-center">
                <div class="card-body">
                    <i class="bi bi-truck display-4 text-primary"></i>
                    <h5 class="card-title mt-3">Pickup Requests</h5>
                    <a href="../farmer/pickup_requests.php" class="btn btn-outline-primary">Manage Pickups</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-lg border-0 text-center">
                <div class="card-body">
                    <i class="bi bi-people-fill display-4 text-warning"></i>
                    <h5 class="card-title mt-3">Community Forum</h5>
                    <a href="../community/community_forum.php" class="btn btn-outline-warning">Join Community</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart + Schemes -->
    <div class="row mt-4">
    <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <h5 class="card-title">Government Schemes</h5>
                    <p>Check latest subsidies and policies.</p>
                    <a href="../farmer/govt_schemes.php" class="btn btn-outline-info">View Schemes</a>
                </div>
            </div>
        </div>
    <div class="container my-5">
  <div class="card shadow-lg border-0 p-4">
    <h3 class="text-success mb-4">🌿 Agri-Waste Management Tips for Farmers</h3>

    <div class="row">
      <div class="col-md-6">
        <div class="alert alert-info shadow-sm mb-3">
          <strong>1. Compost Organic Waste:</strong> Convert crop residues, leaves, and cow dung into nutrient-rich compost to improve soil quality.
        </div>
        <div class="alert alert-info shadow-sm mb-3">
          <strong>2. Reuse Crop Residue:</strong> Use crop leftovers like sugarcane leaves or wheat stalks as mulch or livestock bedding instead of burning them.
        </div>
        <div class="alert alert-info shadow-sm mb-3">
          <strong>3. Avoid Burning Waste:</strong> Burning agri-waste causes air pollution and loss of soil nutrients. Use eco-friendly alternatives like biogas units.
        </div>
        <div class="alert alert-info shadow-sm mb-3">
          <strong>4. Separate Plastic Waste:</strong> Keep fertilizer bags, wrappers, and pipes separately for recycling pickup.
        </div>
        <div class="alert alert-info shadow-sm mb-3">
          <strong>5. Dry and Store Agri Waste:</strong> Keep your dry waste in one place to make it easy for pickup and recycling.
        </div>
      </div>

      <div class="col-md-6">
        <div class="alert alert-info shadow-sm mb-3">
          <strong>6. E-Waste on Farm?</strong> Old electric pumps, batteries, or sensors should be donated via e-waste pickup request.
        </div>
        <div class="alert alert-info shadow-sm mb-3">
          <strong>7. Recycle Plastic Drums & Cans:</strong> Don't throw pesticide containers — clean & send them for recycling.
        </div>
        <div class="alert alert-info shadow-sm mb-3">
          <strong>8. Earn Points for Recycling:</strong> You may get cashback or rewards for verified agri-waste pickups. Stay tuned on your dashboard!
        </div>
        <div class="alert alert-info shadow-sm mb-3">
          <strong>9. Share with Community:</strong> Post your agri-waste reuse ideas in the community forum to inspire others.
        </div>
        <div class="alert alert-info shadow-sm mb-3">
          <strong>10. Use the AI Classifier:</strong> Upload agri-waste photos and let our system tell you what's recyclable!
        </div>
      </div>
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
        labels: [
            'Crop Residue', 'Animal Manure', 'Fruit and Vegetable Waste',
            'Agrochemical Containers', 'Plastic Mulch', 'Spoiled Grain', 'Weeds & Grass'
        ],
        datasets: [{
            label: 'Waste in Kg',
            data: [
                <?= $waste_data['Crop Residue'] ?>,
                <?= $waste_data['Animal Manure'] ?>,
                <?= $waste_data['Fruit and Vegetable Waste'] ?>,
                <?= $waste_data['Agrochemical Containers'] ?>,
                <?= $waste_data['Plastic Mulch'] ?>,
                <?= $waste_data['Spoiled Grain'] ?>,
                <?= $waste_data['Weeds & Grass'] ?>
            ],
            backgroundColor: ['#4caf50','#795548','#ff9800','#9c27b0','#2196f3','#f44336','#8bc34a']
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
    }
});

// AJAX Mark All Notifications as Read
document.getElementById('markAllRead')?.addEventListener('click', () => {
    fetch('mark_notifications_read.php', { method: 'POST' })
    .then(response => response.text())
    .then(() => {
        document.getElementById('notificationList').innerHTML = '<p>All notifications marked as read.</p>';
    });
});
</script>
</body>
</html>
