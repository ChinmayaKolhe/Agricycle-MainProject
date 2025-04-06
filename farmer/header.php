<!-- header.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="../dashboard/farmer_dashboard.php">
            <img src="../assets/images/logo.png" alt="AgriCycle" class="logo me-2" width="60" height="60">
            <span class="fs-4 fw-bold">AgriCycle</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link position-relative px-3" href="../dashboard/farmer_dashboard.php">
                        <i class="bi bi-house-door me-1"></i> Home
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning">
                            <span class="visually-hidden">Home</span>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative px-3" href="../marketplace/index.php">
                        <i class="bi bi-shop me-1"></i> Marketplace
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning">
                            <span class="visually-hidden">Marketplace</span>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative px-3" href="../community/community_forum.php">
                        <i class="bi bi-people me-1"></i> Community
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning">
                            <span class="visually-hidden">Community</span>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative px-3" href="../farmer/profile.php">
                        <i class="bi bi-person-circle me-1"></i> Profile
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning">
                            <span class="visually-hidden">Profile</span>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative px-3" href="../auth/logout.php">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning">
                            <span class="visually-hidden">Logout</span>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    /* Custom Navbar Styling */
    .navbar {
        background: linear-gradient(135deg, #2e7d32, #1b5e20) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }
    
    .navbar-brand {
        transition: transform 0.3s ease;
    }
    
    .navbar-brand:hover {
        transform: scale(1.05);
    }
    
    .nav-link {
        font-weight: 500;
        letter-spacing: 0.5px;
        margin: 0 0.25rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        position: relative;
        color: rgba(255, 255, 255, 0.85) !important;
    }
    
    .nav-link:hover, .nav-link:focus {
        color: white !important;
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
    }
    
    .nav-link i {
        transition: transform 0.3s ease;
    }
    
    .nav-link:hover i {
        transform: scale(1.2);
    }
    
    .navbar-toggler {
        border: none;
        padding: 0.5rem;
    }
    
    .navbar-toggler:focus {
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.25);
    }
    
    /* Active link styling */
    .nav-item.active .nav-link {
        color: white !important;
        font-weight: 600;
        background-color: rgba(255, 255, 255, 0.15);
    }
    
    /* Logo styling */
    .logo {
        transition: transform 0.3s ease;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
    }
    
    .logo:hover {
        transform: rotate(10deg);
    }
    
    /* Badge animation */
    .badge {
        transition: all 0.3s ease;
        opacity: 0;
        font-size: 0.6rem;
    }
    
    .nav-link:hover .badge {
        opacity: 1;
        transform: translate(-10px, -5px);
    }
    
    /* Responsive adjustments */
    @media (max-width: 992px) {
        .navbar-nav {
            padding: 1rem 0;
        }
        
        .nav-link {
            margin: 0.25rem 0;
            padding: 0.75rem 1rem !important;
        }
        
        .nav-link:hover {
            transform: translateX(5px);
        }
    }
</style>

<script>
    // Add active class to current page link
    document.addEventListener('DOMContentLoaded', function() {
        const currentPage = location.pathname.split('/').pop() || 'farmer_dashboard.php';
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            const linkPage = link.getAttribute('href').split('/').pop();
            if (currentPage === linkPage || 
                (currentPage === 'index.php' && linkPage === 'farmer_dashboard.php')) {
                link.parentElement.classList.add('active');
            }
        });
        
        // Add animation to navbar on scroll
        const navbar = document.querySelector('.navbar');
        let lastScroll = 0;
        
        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll <= 0) {
                navbar.style.transform = 'translateY(0)';
                navbar.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
            } 
            else if (currentScroll > lastScroll) {
                // Scroll down
                navbar.style.transform = 'translateY(-100%)';
            } 
            else {
                // Scroll up
                navbar.style.transform = 'translateY(0)';
                navbar.style.boxShadow = '0 8px 20px rgba(0, 0, 0, 0.15)';
            }
            
            lastScroll = currentScroll;
        });
    });
</script>