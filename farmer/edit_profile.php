<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'farmer') {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/db_connect.php';

$farmer_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    $update_query = "UPDATE farmers SET name='$name', email='$email', phone='$phone', location='$location'";
    
    // Handle profile photo upload
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === 0) {
        $target_dir = "../uploads/farmer_photos/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $filename = "farmer_" . $farmer_id . "_" . basename($_FILES["profile_photo"]["name"]);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
            $photo_path = "uploads/farmer_photos/" . $filename;
            $update_query .= ", profile_photo='$photo_path'";
        }
    }

    $update_query .= " WHERE id = $farmer_id";
    mysqli_query($conn, $update_query);

    header("Location: profile.php");
    exit();
}

$query = "SELECT * FROM farmers WHERE id = $farmer_id";
$result = mysqli_query($conn, $query);
$farmer = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | AgriCycle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
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
            background-color: #f8f9fa;
            background-image: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            background-blend-mode: overlay;
            background-color: rgba(248, 249, 250, 0.9);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .edit-profile-container {
            max-width: 700px;
            margin: 2rem auto;
        }
        
        .edit-profile-card {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            transition: all 0.3s ease;
        }
        
        .edit-profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        
        .edit-profile-header {
            background: linear-gradient(135deg, var(--sun-yellow), var(--harvest-orange));
            padding: 2rem;
            text-align: center;
            color: #333;
            position: relative;
            overflow: hidden;
        }
        
        .edit-profile-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 70%);
            transform: rotate(30deg);
        }
        
        .edit-profile-header h2 {
            position: relative;
            font-weight: 700;
            margin-bottom: 0;
        }
        
        .edit-profile-header i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: inline-block;
        }
        
        .edit-profile-body {
            padding: 2.5rem;
            background: white;
        }
        
        .profile-photo-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin: 0 auto 1.5rem;
            display: block;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .profile-photo-preview:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .default-photo {
            font-size: 5rem;
            color: var(--light-green);
            background: white;
            border-radius: 50%;
            padding: 1rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 150px;
            height: 150px;
            cursor: pointer;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--earth-brown);
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.25rem rgba(46, 125, 50, 0.25);
        }
        
        .file-upload-wrapper {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .file-upload-label {
            display: block;
            padding: 0.75rem 1rem;
            background-color: #f8f9fa;
            border: 1px dashed #ddd;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .file-upload-label:hover {
            background-color: #e9ecef;
            border-color: var(--primary-green);
        }
        
        .file-upload-input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .btn-update {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
        }
        
        .btn-update:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(46, 125, 50, 0.4);
            color: white;
            background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
        }
        
        .btn-cancel {
            background: white;
            color: #6c757d;
            border: 1px solid #dee2e6;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-cancel:hover {
            background: #f8f9fa;
            color: #495057;
            transform: translateY(-3px);
        }
        
        @media (max-width: 768px) {
            .edit-profile-container {
                padding: 0 1rem;
            }
            
            .edit-profile-body {
                padding: 1.5rem;
            }
            
            .profile-photo-preview, .default-photo {
                width: 120px;
                height: 120px;
            }
        }
        
        /* Animation for form elements */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-form-item {
            animation: fadeInUp 0.6s ease forwards;
        }
    </style>
</head>
<body>
<div class="edit-profile-container animate__animated animate__fadeIn">
    <div class="edit-profile-card">
        <div class="edit-profile-header">
            <i class="bi bi-pencil-square"></i>
            <h2>Edit Your Profile</h2>
        </div>
        
        <div class="edit-profile-body">
            <form method="post" enctype="multipart/form-data">
                <!-- Profile Photo Preview -->
                <div class="text-center mb-4">
                    <?php if (!empty($farmer['profile_photo'])): ?>
                        <img src="../<?= htmlspecialchars($farmer['profile_photo']) ?>" id="profilePhotoPreview" class="profile-photo-preview animate__animated animate__zoomIn" alt="Profile Photo">
                    <?php else: ?>
                        <div id="defaultPhotoPreview" class="default-photo animate__animated animate__zoomIn">
                            <i class="bi bi-person-circle"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Profile Photo Upload -->
                <div class="file-upload-wrapper animate-form-item" style="animation-delay: 0.1s">
                    <label class="form-label">Profile Photo</label>
                    <div class="file-upload-label">
                        <i class="bi bi-cloud-arrow-up fs-4"></i>
                        <p class="mb-0">Click to upload a new photo</p>
                        <small class="text-muted">(JPG, PNG - Max 2MB)</small>
                        <input type="file" name="profile_photo" id="profilePhotoInput" class="file-upload-input" accept="image/*">
                    </div>
                </div>

                <!-- Name Field -->
                <div class="mb-4 animate-form-item" style="animation-delay: 0.2s">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($farmer['name']) ?>" required>
                </div>

                <!-- Email Field -->
                <div class="mb-4 animate-form-item" style="animation-delay: 0.3s">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($farmer['email']) ?>" required>
                </div>

                <!-- Phone Field -->
                <div class="mb-4 animate-form-item" style="animation-delay: 0.4s">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="form-control" value="<?= htmlspecialchars($farmer['phone']) ?>" required>
                </div>

                <!-- Location Field -->
                <div class="mb-4 animate-form-item" style="animation-delay: 0.5s">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" id="location" name="location" class="form-control" value="<?= htmlspecialchars($farmer['location']) ?>" required>
                </div>

                <!-- Form Buttons -->
                <div class="d-flex justify-content-between mt-5">
                    <a href="profile.php" class="btn btn-cancel animate__animated animate__fadeInLeft">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-update animate__animated animate__fadeInRight">
                        <i class="bi bi-check-circle"></i> Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Profile photo preview functionality
    document.addEventListener('DOMContentLoaded', function() {
        const profilePhotoInput = document.getElementById('profilePhotoInput');
        const profilePhotoPreview = document.getElementById('profilePhotoPreview');
        const defaultPhotoPreview = document.getElementById('defaultPhotoPreview');
        
        if (profilePhotoInput) {
            profilePhotoInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        if (profilePhotoPreview) {
                            profilePhotoPreview.src = e.target.result;
                        } else if (defaultPhotoPreview) {
                            defaultPhotoPreview.innerHTML = `<img src="${e.target.result}" class="profile-photo-preview" alt="Profile Photo">`;
                        }
                    }
                    
                    reader.readAsDataURL(file);
                }
            });
        }
        
        // Add animation to form elements when they come into view
        const animateItems = document.querySelectorAll('.animate-form-item');
        
        animateItems.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            item.style.transition = `all 0.6s ease ${index * 0.1}s`;
            
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 100);
        });
    });
</script>
</body>
</html>