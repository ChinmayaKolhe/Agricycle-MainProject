<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - AgriCycle</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Animate On Scroll (AOS) -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

    <!-- Navigation Bar -->
    <?php include 'includes/navbar.php'; ?>

    <!-- About Section -->
    <section class="container my-5">
        <h2 class="text-center mb-4" data-aos="fade-up">About AgriCycle</h2>
        <p class="text-center" data-aos="fade-up">We are committed to transforming agricultural waste into sustainable opportunities.</p>

        <div class="row align-items-center">
            <div class="col-md-6" data-aos="fade-right">
                <img src="assets/images/about.png" class="img-fluid rounded shadow-sm" alt="About Us">
            </div>
            <div class="col-md-6" data-aos="fade-left">
                <b><p>
                    AgriCycle is a pioneering platform that helps farmers manage agricultural waste efficiently. 
                    We provide an eco-friendly marketplace where waste can be repurposed into useful products, 
                    fostering sustainability and economic growth.
                </p></b>
                <h4>Our Mission</h4>
                <p>To promote sustainable waste management solutions for farmers and agricultural businesses.</p>
                <h4>Our Vision</h4>
                <p>To create a cleaner, greener, and more profitable agricultural ecosystem.</p>
            </div>
        </div>
    </section>

    <!-- Our Team -->
    <section class="container my-5">
        <h2 class="text-center mb-4" data-aos="fade-up">Meet Our Team</h2>
        <div class="row text-center">
            <div class="col-md-4" data-aos="zoom-in">
                <img src="assets/images/team1.jpg" class="rounded-circle mb-2" width="150" height="150" alt="Team Member">
                <h5>Chinmaya Bhushan Kolhe</h5>
                <p>Founder & Developer</p>
            </div>
            <div class="col-md-4" data-aos="zoom-in">
                <img src="assets/images/team2.jpg" class="rounded-circle mb-2" width="150" height="150" alt="Team Member">
                <h5>Akanksha</h5>
                <p>Full Stack Developer</p>
            </div>
            <div class="col-md-4" data-aos="zoom-in">
                <img src="assets/images/team3.jpg" class="rounded-circle mb-2" width="150" height="150" alt="Team Member">
                <h5>Lubdha</h5>
                <p>AI/ML Developer</p>
            </div>
            <div class="col-md-4" data-aos="zoom-in">
                <img src="assets/images/team1.jpg" class="rounded-circle mb-2" width="150" height="150" alt="Team Member">
                <h5>Vaishnavi Kale</h5>
                <p>Full Stack Developer</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>
