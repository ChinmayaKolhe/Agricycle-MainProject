<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --primary-green: #4CAF50;
            --dark-green: #388E3C;
            --light-green: #C8E6C9;
            --soil-brown: #8D6E63;
            --harvest-gold: #FFD54F;
            --text-dark: #333;
            --text-light: #f5f5f5;
            --transition-speed: 0.3s;
        }
        
        .agri-navbar {
            background: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 0.8rem 1rem;
            position: relative;
            z-index: 1000;
            font-family: 'Poppins', sans-serif;
        }
        
        .agri-navbar::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-green), var(--harvest-gold), var(--primary-green));
            background-size: 200% 100%;
            animation: gradientFlow 8s ease infinite;
        }
        
        @keyframes gradientFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            background: linear-gradient(to right, var(--primary-green), var(--dark-green));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: flex;
            align-items: center;
            transition: all var(--transition-speed) ease;
        }
        
        .navbar-brand:hover {
            transform: translateY(-2px);
        }
        
        .navbar-brand i {
            margin-right: 8px;
            font-size: 1.8rem;
        }
        
        .nav-link {
            color: var(--text-dark);
            font-weight: 500;
            padding: 0.5rem 1rem;
            margin: 0 0.2rem;
            border-radius: 6px;
            transition: all var(--transition-speed) ease;
            position: relative;
        }
        
        .nav-link:hover {
            color: var(--primary-green);
            background: rgba(76, 175, 80, 0.1);
            transform: translateY(-2px);
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--primary-green);
            transition: all var(--transition-speed) ease;
        }
        
        .nav-link:hover::after {
            width: 70%;
            left: 15%;
        }
        
        .nav-link.active {
            color: var(--primary-green);
            font-weight: 600;
        }
        
        .nav-link.active::after {
            width: 70%;
            left: 15%;
        }
        
        .text-primary-nav {
            color: var(--primary-green) !important;
        }
        
        .text-danger-nav {
            color: #e53935 !important;
        }
        
        .navbar-toggler {
            border: none;
            padding: 0.5rem;
            transition: all var(--transition-speed) ease;
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(76, 175, 80, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
            transition: transform 0.3s ease;
        }
        
        .navbar-toggler[aria-expanded="true"] .navbar-toggler-icon {
            transform: rotate(90deg);
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 0.5rem;
            margin-top: 0.5rem;
            animation: fadeIn 0.2s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .dropdown-item {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: all var(--transition-speed) ease;
        }
        
        .dropdown-item:hover {
            background: rgba(76, 175, 80, 0.1);
            color: var(--primary-green);
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 8px;
            border: 2px solid var(--light-green);
            transition: all var(--transition-speed) ease;
        }
        
        .user-avatar:hover {
            transform: scale(1.1);
            border-color: var(--primary-green);
        }
        
        @media (max-width: 991.98px) {
            .navbar-collapse {
                padding: 1rem 0;
                background: white;
                border-radius: 0 0 12px 12px;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
                margin-top: 0.5rem;
            }
            
            .nav-item {
                margin: 0.3rem 0;
            }
            
            .nav-link {
                padding: 0.8rem 1rem;
            }
            
            .nav-link::after {
                bottom: 8px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="agri-navbar navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="../marketplace/index.php">
                <i class="fas fa-leaf"></i>AgriCycle Marketplace
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Home (Visible to all) -->
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="../marketplace/index.php">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>

                    <!-- Sell Item for Farmer -->
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'farmer'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'add_item.php' ? 'active' : ''; ?>" href="../marketplace/add_item.php">
                                <i class="fas fa-plus-circle"></i> Sell Item
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- My Orders for Buyer -->
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'buyer'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : ''; ?>" href="../orders.php">
                                <i class="fas fa-shopping-bag"></i> My Orders
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- User Profile Dropdown -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php if (isset($_SESSION['avatar'])): ?>
                                    <img src="<?php echo $_SESSION['avatar']; ?>" class="user-avatar" alt="User Avatar">
                                <?php else: ?>
                                    <i class="fas fa-user-circle"></i>
                                <?php endif; ?>
                                <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Account'; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="../profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="../settings.php"><i class="fas fa-cog me-2"></i>Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger-nav" href="../auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link text-primary-nav" href="../auth/login.php">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add smooth scrolling to all links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add active class to current page link
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.nav-link');
            const currentPage = location.pathname.split('/').pop();
            
            navLinks.forEach(link => {
                const linkPage = link.getAttribute('href').split('/').pop();
                if (linkPage === currentPage) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>