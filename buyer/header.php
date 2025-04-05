<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['buyer', 'farmer'])) {
    header("Location: ../auth/login.php");
    exit();
}
?>


<style>
/* Custom Navbar Styling */
.navbar-custom {
    background-color: #004d40; /* Dark Green */
    padding: 15px 0;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.navbar-custom .navbar-brand {
    color: white;
    font-size: 1.8rem;
    font-weight: bold;
    display: flex;
    align-items: center;
}

.navbar-custom .navbar-brand img {
    height: 40px; /* Adjust logo size */
    margin-right: 10px;
}

.navbar-custom .navbar-nav .nav-link {
    color: white !important;
    font-size: 1.1rem;
    margin: 0 10px;
    transition: color 0.3s ease-in-out;
}

.navbar-custom .navbar-nav .nav-link:hover {
    color: #ffdd57 !important; /* Yellow hover effect */
}

.navbar-toggler {
    border: none;
}

.navbar-toggler-icon {
    filter: invert(1); /* Makes icon visible */
}
</style>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="buyer_dashboard.php">
            <img src="../assets/images/logo.png" alt="AgriCycle Logo"> AgriCycle
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../marketplace/index.php"><i class="bi bi-shop"></i> Marketplace</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../wishlist.php"><i class="bi bi-heart"></i> Wishlist</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../buyer/buyer.php"><i class="bi bi-heart"></i> Chatbot</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../orders.php"><i class="bi bi-bag"></i> Orders</a>
                </li>
            
                <li class="nav-item">
                    <a class="nav-link text-danger" href="../auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
