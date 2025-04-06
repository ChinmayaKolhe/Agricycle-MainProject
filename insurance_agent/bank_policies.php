<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'insurance_agent') {
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('Please login as insurance agent', 'error');
                setTimeout(() => {
                    window.location.href = '../auth/login.php';
                }, 2500);
            });
          </script>";
    exit();
}
include '../config/db_connect.php';

// Handle add policy form submission
if (isset($_POST['add_policy'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $bank_link = mysqli_real_escape_string($conn, $_POST['bank_link']);
    $agent_id = $_SESSION['user_id'];

    $uploadDir = "../uploads/policies/";
    $pdfPath = "";

    // Handle PDF Upload
    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['pdf_file']['tmp_name'];
        $fileName = basename($_FILES['pdf_file']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($fileExt === 'pdf') {
            $newName = uniqid('policy_', true) . '.' . $fileExt;
            $destination = $uploadDir . $newName;
            if (move_uploaded_file($fileTmp, $destination)) {
                $pdfPath = "uploads/policies/" . $newName;
            }
        }
    }

    $sql = "INSERT INTO bank_policies (agent_id, name, pdf_path, bank_link)
            VALUES ('$agent_id', '$name', '$pdfPath', '$bank_link')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification('Policy added successfully!', 'success');
                });
              </script>";
    } else {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification('Error: " . addslashes(mysqli_error($conn)) . "', 'error');
                });
              </script>";
    }
}

// Fetch agent's policies
$agent_id = $_SESSION['user_id'];
$policies = mysqli_query($conn, "SELECT * FROM bank_policies WHERE agent_id = '$agent_id'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Policies | AgriCycle</title>
    <style>
        :root {
            --primary-green: #4CAF50;
            --dark-green: #388E3C;
            --light-green: #C8E6C9;
            --soil-brown: #8D6E63;
            --harvest-gold: #FFD54F;
            --error-red: #F44336;
            --success-green: #4CAF50;
            --text-dark: #333;
            --text-light: #f5f5f5;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            color: var(--text-dark);
            background-image: linear-gradient(to bottom, rgba(201, 255, 203, 0.3), rgba(255, 255, 255, 0.8)), 
                              url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><path fill="%234CAF50" fill-opacity="0.1" d="M30,10 Q50,0 70,10 Q90,20 80,40 Q70,60 50,70 Q30,60 20,40 Q10,20 30,10 Z"/></svg>');
            background-size: 200px;
        }
        
        .notification-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        
        .notification {
            position: relative;
            padding: 15px 25px;
            margin-bottom: 15px;
            border-radius: 8px;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            transform: translateX(120%);
            animation: slideIn 0.5s forwards;
            overflow: hidden;
        }
        
        .notification.success {
            background-color: var(--success-green);
        }
        
        .notification.error {
            background-color: var(--error-red);
        }
        
        .notification-icon {
            margin-right: 15px;
            font-size: 24px;
        }
        
        @keyframes slideIn {
            to {
                transform: translateX(0);
            }
        }
        
        .agri-navbar {
            background: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 0.8rem 1rem;
            position: relative;
            z-index: 1000;
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
        }
        
        .navbar-brand i {
            margin-right: 8px;
        }
        
        .nav-link {
            color: var(--text-dark);
            font-weight: 500;
            padding: 0.5rem 1rem;
            margin: 0 0.2rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--primary-green);
            background: rgba(76, 175, 80, 0.1);
        }
        
        .nav-link.active {
            color: var(--primary-green);
            font-weight: 600;
        }
        
        .text-danger-nav {
            color: #e53935 !important;
        }
        
        .policy-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .section-title {
            color: var(--primary-green);
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 10px;
            position: relative;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--harvest-gold);
            border-radius: 2px;
        }
        
        .section-subtitle {
            color: var(--soil-brown);
            font-size: 1rem;
        }
        
        .add-policy-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-bottom: 40px;
            border-left: 5px solid var(--primary-green);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-dark);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
            outline: none;
        }
        
        .file-input-container {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }
        
        .file-input-button {
            border: 2px dashed var(--primary-green);
            border-radius: 8px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: rgba(76, 175, 80, 0.05);
        }
        
        .file-input-button:hover {
            background: rgba(76, 175, 80, 0.1);
            border-color: var(--dark-green);
        }
        
        .file-input-button i {
            font-size: 40px;
            color: var(--primary-green);
            margin-bottom: 15px;
            display: block;
        }
        
        .file-input-button span {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .file-input-button small {
            color: var(--soil-brown);
            font-size: 12px;
        }
        
        .file-input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .file-name {
            margin-top: 10px;
            font-size: 14px;
            color: var(--primary-green);
            font-weight: 500;
            display: none;
        }
        
        .btn {
            padding: 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: var(--primary-green);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--dark-green);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }
        
        .policies-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }
        
        .policy-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 20px;
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary-green);
        }
        
        .policy-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .policy-name {
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--primary-green);
            margin-bottom: 10px;
        }
        
        .policy-link {
            display: inline-block;
            margin-bottom: 15px;
            color: var(--soil-brown);
            text-decoration: none;
            transition: all 0.3s ease;
            word-break: break-all;
        }
        
        .policy-link:hover {
            color: var(--primary-green);
            text-decoration: underline;
        }
        
        .btn-pdf {
            background: rgba(244, 67, 54, 0.1);
            color: #f44336;
            padding: 8px 15px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-pdf:hover {
            background: rgba(244, 67, 54, 0.2);
            color: #d32f2f;
        }
        
        .no-policies {
            text-align: center;
            padding: 50px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .no-policies-icon {
            font-size: 60px;
            color: var(--light-green);
            margin-bottom: 20px;
        }
        
        .no-policies-text {
            font-size: 18px;
            color: var(--soil-brown);
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .policies-grid {
                grid-template-columns: 1fr;
            }
            
            .add-policy-card {
                padding: 20px;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="notification-container" id="notificationContainer"></div>
    
    <div class="policy-container">
        <div class="section-header">
            <h1 class="section-title">Bank Policies Management</h1>
            <p class="section-subtitle">Add and manage agricultural insurance policies</p>
        </div>
        
        <div class="add-policy-card">
            <h3 class="text-center mb-4" style="color: var(--primary-green);">Add New Policy</h3>
            <form method="POST" enctype="multipart/form-data" id="policyForm">
                <div class="form-group">
                    <label class="form-label" for="name">Policy Title</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter policy name" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="bank_link">Bank Portal Link</label>
                    <input type="url" id="bank_link" name="bank_link" class="form-control" placeholder="https://example.com" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Policy Document (PDF)</label>
                    <div class="file-input-container">
                        <div class="file-input-button" id="fileInputButton">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Click to upload PDF</span>
                            <small>Only PDF files accepted</small>
                            <div class="file-name" id="fileName"></div>
                        </div>
                        <input type="file" name="pdf_file" id="pdf_file" class="file-input" accept=".pdf" required>
                    </div>
                </div>
                
                <button type="submit" name="add_policy" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Add Policy
                </button>
            </form>
        </div>
        
        <h3 class="text-center mb-4" style="color: var(--primary-green);">Your Policies</h3>
        
        <?php if (mysqli_num_rows($policies) > 0): ?>
            <div class="policies-grid">
                <?php while ($row = mysqli_fetch_assoc($policies)): ?>
                    <div class="policy-card">
                        <div class="policy-name"><?= htmlspecialchars($row['name']) ?></div>
                        <a href="<?= htmlspecialchars($row['bank_link']) ?>" target="_blank" class="policy-link">
                            <i class="fas fa-external-link-alt"></i> <?= htmlspecialchars($row['bank_link']) ?>
                        </a>
                        <?php if (!empty($row['pdf_path'])): ?>
                            <a href="../<?= $row['pdf_path'] ?>" target="_blank" class="btn-pdf">
                                <i class="fas fa-file-pdf"></i> View Policy PDF
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-policies">
                <div class="no-policies-icon">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3>No Policies Added Yet</h3>
                <p class="no-policies-text">You haven't added any bank policies yet. Add your first policy above.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        function showNotification(message, type) {
            const container = document.getElementById('notificationContainer');
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            
            const icon = document.createElement('span');
            icon.className = 'notification-icon';
            
            switch(type) {
                case 'success':
                    icon.innerHTML = '<i class="fas fa-check-circle"></i>';
                    break;
                case 'error':
                    icon.innerHTML = '<i class="fas fa-exclamation-circle"></i>';
                    break;
            }
            
            const text = document.createElement('span');
            text.textContent = message;
            
            notification.appendChild(icon);
            notification.appendChild(text);
            container.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'fadeOut 0.5s forwards';
                setTimeout(() => {
                    notification.remove();
                }, 500);
            }, 3000);
        }
        
        // File input handling
        const fileInput = document.getElementById('pdf_file');
        const fileInputButton = document.getElementById('fileInputButton');
        const fileNameDisplay = document.getElementById('fileName');
        
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                if (file.type !== 'application/pdf') {
                    showNotification('Only PDF files are allowed', 'error');
                    this.value = '';
                    return;
                }
                
                fileNameDisplay.textContent = file.name;
                fileNameDisplay.style.display = 'block';
                fileInputButton.querySelector('span').textContent = 'File selected';
                fileInputButton.querySelector('small').textContent = 'Click to change';
                fileInputButton.style.borderColor = 'var(--primary-green)';
                fileInputButton.style.backgroundColor = 'rgba(76, 175, 80, 0.1)';
            }
        });
        
        // Form submission handling
        document.getElementById('policyForm').addEventListener('submit', function() {
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding Policy...';
            submitButton.disabled = true;
        });
    </script>
</body>
</html>