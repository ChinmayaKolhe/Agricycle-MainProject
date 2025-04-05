<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - AgriCycle</title>

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

    <!-- Contact Section -->
    <section class="container my-5">
        <h2 class="text-center mb-4" data-aos="fade-up">Contact Us</h2>
        <p class="text-center" data-aos="fade-up">Have questions or need support? Feel free to reach out to us.</p>

        <div class="row">
            <div class="col-md-6" data-aos="fade-right">
                <form action="process_contact.php" method="POST" class="p-4 border rounded shadow-sm bg-light">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Send Message</button>
                </form>
            </div>
            <div class="col-md-6" data-aos="fade-left">
                <div class="p-4 border rounded shadow-sm bg-light">
                    <h4>Our Office</h4>
                    <p><i class="fas fa-map-marker-alt"></i> 123 AgriCycle Street, Pune, India</p>
                    <p><i class="fas fa-envelope"></i> support@agricycle.com</p>
                    <p><i class="fas fa-phone"></i> +91 98765 43210</p>
                    <iframe src="https://www.google.com/maps/embed?..."></iframe>
                </div>
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
