<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriCycle - Transforming Agricultural Waste into Opportunity</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    
    <!-- Animate On Scroll (AOS) -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

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
            font-family: 'Poppins', sans-serif;
            color: #333;
            overflow-x: hidden;
        }
        
        .navbar {
            background-color: white;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            padding: 15px 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--dark-green);
            display: flex;
            align-items: center;
        }
        
        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }
        
        .nav-link {
            font-weight: 500;
            color: #555;
            margin: 0 10px;
            position: relative;
        }
        
        .nav-link:hover {
            color: var(--primary-green);
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--primary-green);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .btn-success {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
        }
        
        .btn-success:hover {
            background-color: var(--dark-green);
            border-color: var(--dark-green);
        }
        
        .btn-primary {
            background-color: var(--harvest-orange);
            border-color: var(--harvest-orange);
        }
        
        .btn-primary:hover {
            background-color: #e65100;
            border-color: #e65100;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), 
                        url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            padding: 180px 0 120px;
            text-align: center;
        }
        
        .hero-section h1 {
            font-weight: 700;
            font-size: 3rem;
            margin-bottom: 20px;
            text-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }
        
        .hero-section .lead {
            font-size: 1.5rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .hero-section .btn {
            padding: 12px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 30px;
            transition: all 0.3s;
        }
        
        .hero-section .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        /* Features Section */
        .features-section {
            padding: 80px 0;
            background-color: #f9f9f9;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 60px;
            position: relative;
        }
        
        .section-title h2 {
            font-weight: 700;
            color: var(--dark-green);
            margin-bottom: 15px;
        }
        
        .section-title p {
            color: #666;
            max-width: 700px;
            margin: 0 auto;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-green), var(--light-green));
        }
        
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            transition: all 0.3s;
            height: 100%;
            border-top: 5px solid var(--primary-green);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-green);
            margin-bottom: 20px;
        }
        
        .feature-card h3 {
            font-weight: 600;
            color: var(--dark-green);
            margin-bottom: 15px;
        }
        
        /* How It Works Section */
        .how-it-works {
            padding: 80px 0;
            background-color: white;
        }
        
        .step-card {
            text-align: center;
            padding: 30px;
            position: relative;
        }
        
        .step-number {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--light-green), var(--primary-green));
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 auto 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .step-card h3 {
            font-weight: 600;
            color: var(--dark-green);
            margin-bottom: 15px;
        }
        
        /* Benefits Section */
        .benefits-section {
            padding: 80px 0;
            background: linear-gradient(rgba(255,255,255,0.9), rgba(255,255,255,0.9)), 
                        url('https://images.unsplash.com/photo-1464226184884-fa280b87c399?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        .benefit-card {
            background: rgba(255,255,255,0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            transition: all 0.3s;
            height: 100%;
            border-left: 5px solid var(--harvest-orange);
        }
        
        .benefit-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .benefit-card h3 {
            font-weight: 600;
            color: var(--earth-brown);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .benefit-card h3 i {
            margin-right: 10px;
            color: var(--harvest-orange);
        }
        
        /* Testimonials */
        .testimonials-section {
            padding: 80px 0;
            background-color: #f9f9f9;
        }
        
        .testimonial-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin: 15px;
            position: relative;
        }
        
        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 4rem;
            color: rgba(46, 125, 50, 0.1);
            font-family: serif;
            line-height: 1;
        }
        
        .testimonial-text {
            font-style: italic;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
        }
        
        .testimonial-author img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
        }
        
        .author-info h5 {
            font-weight: 600;
            margin-bottom: 0;
            color: var(--dark-green);
        }
        
        .author-info p {
            color: #777;
            font-size: 0.9rem;
            margin-bottom: 0;
        }
        
        /* CTA Section */
        .cta-section {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--light-green), var(--primary-green));
            color: white;
            text-align: center;
        }
        
        .cta-section h2 {
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .cta-section p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.9;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .cta-buttons .btn {
            margin: 10px;
            padding: 12px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 30px;
            transition: all 0.3s;
        }
        
        .cta-buttons .btn-light {
            background-color: white;
            color: var(--primary-green);
        }
        
        .cta-buttons .btn-light:hover {
            background-color: #f8f9fa;
            transform: translateY(-3px);
        }
        
        .cta-buttons .btn-outline-light {
            border: 2px solid white;
            color: white;
        }
        
        .cta-buttons .btn-outline-light:hover {
            background-color: white;
            color: var(--primary-green);
            transform: translateY(-3px);
        }
        
        /* Footer */
        .footer {
            background-color: var(--dark-green);
            color: white;
            padding: 50px 0 20px;
        }
        
        .footer-logo {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 20px;
            display: inline-block;
        }
        
        .footer-about p {
            opacity: 0.8;
            margin-bottom: 20px;
        }
        
        .footer-links h4 {
            font-weight: 600;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-links h4::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background-color: var(--light-green);
        }
        
        .footer-links ul {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 10px;
        }
        
        .footer-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .footer-links a:hover {
            color: white;
            padding-left: 5px;
        }
        
        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255,255,255,0.1);
            border-radius: 50%;
            color: white;
            margin-right: 10px;
            transition: all 0.3s;
        }
        
        .social-links a:hover {
            background-color: var(--light-green);
            transform: translateY(-3px);
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 20px;
            margin-top: 30px;
            text-align: center;
            opacity: 0.7;
            font-size: 0.9rem;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .hero-section {
                padding: 120px 0 80px;
            }
            
            .hero-section h1 {
                font-size: 2.2rem;
            }
            
            .hero-section .lead {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#"><img src="assets/images/logo.png" alt="AgriCycle" class="logo"> AgriCycle</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <li class="nav-item"><a class="btn btn-success" href="auth/login.php">Login</a></li>
                    <li class="nav-item"><a class="btn btn-primary ms-2" href="auth/register.php">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section" data-aos="fade">
        <div class="container">
            <h1 data-aos="fade-up">Transforming Agricultural Waste into Opportunity</h1>
            <p class="lead" data-aos="fade-up" data-aos-delay="100">Connecting farmers with manufacturers to create a sustainable ecosystem</p>
            <div data-aos="fade-up" data-aos-delay="200">
                <a href="auth/register.php" class="btn btn-lg btn-primary me-2">Join as Farmer</a>
                <a href="auth/register.php" class="btn btn-lg btn-success">Join as Manufacturer</a>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Why AgriCycle?</h2>
                <p>Our platform bridges the gap between agricultural waste producers and industrial users, creating value from what was once considered waste.</p>
            </div>
            
            <div class="row">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-recycle"></i>
                        </div>
                        <h3>Waste to Wealth</h3>
                        <p>Convert agricultural byproducts into profitable commodities instead of burning or dumping them.</p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h3>Crop Insurance</h3>
                        <p>Integrated insurance module protects your crops against natural calamities and market fluctuations.</p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h3>Direct Marketplace</h3>
                        <p>Connect directly with manufacturers and compost producers without middlemen for better prices.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>How AgriCycle Works</h2>
                <p>Simple steps to turn your agricultural waste into income while contributing to sustainability</p>
            </div>
            
            <div class="row">
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h3>List Your Waste</h3>
                        <p>Farmers register and list their agricultural byproducts with details like type, quantity, and location.</p>
                    </div>
                </div>
                
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h3>Get Discovered</h3>
                        <p>Manufacturers browse listings and connect with farmers for potential waste materials they need.</p>
                    </div>
                </div>
                
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h3>Secure Transaction</h3>
                        <p>Our platform facilitates safe negotiations, payments, and logistics coordination.</p>
                    </div>
                </div>
                
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <h3>Earn & Sustain</h3>
                        <p>Farmers earn from waste, manufacturers get raw materials, and the environment benefits.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="benefits-section">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Benefits for Everyone</h2>
                <p>AgriCycle creates a win-win situation for all stakeholders in the agricultural ecosystem</p>
            </div>
            
            <div class="row">
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="benefit-card">
                        <h3><i class="bi bi-tree"></i> For Farmers</h3>
                        <ul>
                            <li>Additional income stream from agricultural byproducts</li>
                            <li>Reduced waste management costs</li>
                            <li>Crop insurance protection against unforeseen events</li>
                            <li>Access to wider market for agricultural outputs</li>
                            <li>Contribution to sustainable farming practices</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="benefit-card">
                        <h3><i class="bi bi-building"></i> For Manufacturers</h3>
                        <ul>
                            <li>Reliable source of raw materials for compost production</li>
                            <li>Cost-effective alternative to traditional raw materials</li>
                            <li>Traceable and sustainable supply chain</li>
                            <li>Positive environmental impact for CSR reporting</li>
                            <li>Direct connection with producers eliminates middlemen</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-12" data-aos="fade-up" data-aos-delay="300">
                    <div class="benefit-card">
                        <h3><i class="bi bi-globe"></i> Environmental Impact</h3>
                        <ul>
                            <li>Reduces agricultural burning and associated air pollution</li>
                            <li>Decreases landfill waste from farm byproducts</li>
                            <li>Promotes circular economy in agriculture</li>
                            <li>Lowers carbon footprint of manufacturing processes</li>
                            <li>Encourages sustainable waste management practices</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Success Stories</h2>
                <p>Hear from farmers and manufacturers who have transformed their operations with AgriCycle</p>
            </div>
            
            <div class="row">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimonial-card">
                        <p class="testimonial-text">AgriCycle helped me earn ₹25,000 from rice husk that I used to burn every season. Now it's a regular income source.</p>
                        <div class="testimonial-author">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Rajesh Kumar">
                            <div class="author-info">
                                <h5>Rajesh Kumar</h5>
                                <p>Rice Farmer, Punjab</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="testimonial-card">
                        <p class="testimonial-text">We've reduced our raw material costs by 40% by sourcing agricultural waste through AgriCycle while meeting sustainability goals.</p>
                        <div class="testimonial-author">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Priya Sharma">
                            <div class="author-info">
                                <h5>Priya Sharma</h5>
                                <p>Compost Manufacturer, Maharashtra</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="testimonial-card">
                        <p class="testimonial-text">When floods damaged my crops, the insurance claim through AgriCycle was processed in 7 days. It saved my family's livelihood.</p>
                        <div class="testimonial-author">
                            <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="Vijay Reddy">
                            <div class="author-info">
                                <h5>Vijay Reddy</h5>
                                <p>Cotton Farmer, Andhra Pradesh</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section" data-aos="fade">
        <div class="container">
            <h2>Ready to Join the Agricultural Revolution?</h2>
            <p>Whether you're a farmer with agricultural byproducts or a manufacturer looking for sustainable raw materials, AgriCycle is your platform.</p>
            <div class="cta-buttons">
                <a href="auth/register.php" class="btn btn-lg btn-light">Sign Up as Farmer</a>
                <a href="auth/register.php" class="btn btn-lg btn-outline-light">Sign Up as Manufacturer</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="footer-about">
                        <span class="footer-logo">AgriCycle</span>
                        <p>Transforming agricultural waste into valuable resources while protecting farmers through innovative insurance solutions.</p>
                        <div class="social-links">
                            <a href="#"><i class="bi bi-facebook"></i></a>
                            <a href="#"><i class="bi bi-twitter"></i></a>
                            <a href="#"><i class="bi bi-instagram"></i></a>
                            <a href="#"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <div class="footer-links">
                        <h4>Quick Links</h4>
                        <ul>
                            <li><a href="index.php">Home</a></li>
                            <li><a href="about.php">About Us</a></li>
                            <li><a href="marketplace.php">Marketplace</a></li>
                            <li><a href="insurance.php">Crop Insurance</a></li>
                            <li><a href="contact.php">Contact</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-links">
                        <h4>For Farmers</h4>
                        <ul>
                            <li><a href="how-it-works.php">How It Works</a></li>
                            <li><a href="pricing.php">Pricing</a></li>
                            <li><a href="resources.php">Farming Resources</a></li>
                            <li><a href="insurance-details.php">Insurance Details</a></li>
                            <li><a href="success-stories.php">Success Stories</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-links">
                        <h4>For Manufacturers</h4>
                        <ul>
                            <li><a href="manufacturers.php">Benefits</a></li>
                            <li><a href="materials.php">Available Materials</a></li>
                            <li><a href="quality-standards.php">Quality Standards</a></li>
                            <li><a href="logistics.php">Logistics Support</a></li>
                            <li><a href="partnerships.php">Partnerships</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 AgriCycle. All rights reserved. | <a href="privacy.php">Privacy Policy</a> | <a href="terms.php">Terms of Service</a></p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    </script>
</body>
</html>