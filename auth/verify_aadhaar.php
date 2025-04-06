<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer_pending') {
    header("Location: ../auth/login.php");
    exit();
}

$farmer_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['aadhaar_card'])) {
    $aadhaar_path = "../uploads/aadhaar/" . basename($_FILES['aadhaar_card']['name']);
    move_uploaded_file($_FILES['aadhaar_card']['tmp_name'], $aadhaar_path);

    // Store path in DB and set verified = 0 (request sent)
    $update = "UPDATE farmers SET aadhaar_path=?, verification_requested=1 WHERE id=?";
    $stmt = mysqli_prepare($conn, $update);
    mysqli_stmt_bind_param($stmt, "si", $aadhaar_path, $farmer_id);
    mysqli_stmt_execute($stmt);

    $msg = "Your Aadhaar has been submitted for verification. Please wait for admin approval.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aadhaar Verification | AgriCycle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            background-image: url('../assets/images/farm-field.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-blend-mode: overlay;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .verification-container {
            max-width: 600px;
            margin: 0 auto;
            animation: fadeIn 0.5s ease-out;
        }
        
        .verification-card {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }
        
        .verification-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        
        .verification-header {
            background: linear-gradient(90deg, var(--primary-green), var(--dark-green));
            color: white;
            padding: 25px;
            text-align: center;
            position: relative;
        }
        
        .verification-header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 15px solid transparent;
            border-right: 15px solid transparent;
            border-top: 15px solid var(--dark-green);
        }
        
        .verification-header h3 {
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .verification-body {
            padding: 30px;
        }
        
        .verification-icon {
            font-size: 4rem;
            color: var(--primary-green);
            margin-bottom: 20px;
            text-align: center;
        }
        
        .verification-text {
            text-align: center;
            margin-bottom: 30px;
            color: #555;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--earth-brown);
            margin-bottom: 8px;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--light-green);
            box-shadow: 0 0 0 3px rgba(129, 199, 132, 0.2);
        }
        
        .btn-verify {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-green);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-verify:hover {
            background-color: var(--dark-green);
            transform: translateY(-2px);
        }
        
        .alert-info {
            background-color: #e8f5e9;
            color: var(--dark-green);
            border-left: 4px solid var(--primary-green);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        
        .file-upload-info {
            background-color: #f5f5f5;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            font-size: 0.9rem;
            color: #666;
            border-left: 3px solid var(--sun-yellow);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 768px) {
            body {
                padding: 20px;
            }
            
            .verification-body {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="verification-container animate__animated animate__fadeIn">
        <div class="verification-card">
            <div class="verification-header">
                <h3><i class="bi bi-shield-check"></i> Aadhaar Verification</h3>
                <p>Complete your AgriCycle account setup</p>
            </div>
            
            <div class="verification-body">
                <?php if (isset($msg)): ?>
                    <div class="alert-info animate__animated animate__fadeIn">
                        <i class="bi bi-check-circle-fill"></i> <?= $msg ?>
                    </div>
                <?php endif; ?>
                
                <div class="verification-icon">
                    <i class="bi bi-person-badge"></i>
                </div>
                
                <p class="verification-text">
                    To ensure secure transactions and protect our community, we require Aadhaar verification for all farmers.
                    Your document will be reviewed within 24-48 hours.
                </p>
                
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="aadhaar_card" class="form-label">Upload Aadhaar Card</label>
                        <input type="file" class="form-control" id="aadhaar_card" name="aadhaar_card" required>
                        <div class="file-upload-info">
                            <i class="bi bi-info-circle"></i> Accepted formats: JPG, PNG, PDF (Max 5MB)
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-verify">
                        <i class="bi bi-cloud-arrow-up"></i> Submit for Verification
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // File input validation
        document.getElementById('aadhaar_card').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const validTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            const maxSize = 5 * 1024 * 1024; // 5MB
            
            if (file && !validTypes.includes(file.type)) {
                alert('Please upload a JPG, PNG, or PDF file.');
                e.target.value = '';
            }
            
            if (file && file.size > maxSize) {
                alert('File size exceeds 5MB limit.');
                e.target.value = '';
            }
        });
    </script>
</body>
</html>