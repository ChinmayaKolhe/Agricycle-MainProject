<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand text-success fw-bold" href="#">AgriCycle Marketplace</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <!-- Home (Visible to all) -->
                <li class="nav-item">
                    <a class="nav-link" href="../marketplace/index.php">Home</a>
                </li>

                <!-- Sell Item for Farmer -->
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'farmer'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../marketplace/add_item.php">Sell an Item</a>
                    </li>
                <?php endif; ?>

                <!-- My Orders for Buyer -->
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'buyer'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../orders.php">My Orders</a>
                    </li>
                <?php endif; ?>

                <!-- Login / Logout -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../auth/logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link text-primary" href="../auth/login.php">Login</a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>
