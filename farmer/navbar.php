<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>


<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">
            <i class="bi bi-leaf"></i> AgriCycle Farmer
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../dashboard/farmer_dashboard.php"><i class="bi bi-house-door"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="waste_requests.php"><i class="bi bi-recycle"></i> Waste Requests</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pickup_requests.php"><i class="bi bi-truck"></i> Pickup Requests</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../marketplace/index.php"><i class="bi bi-shop"></i> Marketplace</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../community/forum.php"><i class="bi bi-people"></i> Community</a>
                </li>
                
            </ul>
        </div>
    </div>
</nav>
