<?php 
session_start();
include '../config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];  
    $adminCodeInput = isset($_POST['admin_code']) ? $_POST['admin_code'] : '';

    // Optional Fields
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $location = $_POST['location'] ?? '';
    $company = $_POST['company'] ?? '';
    $agency = $_POST['agency'] ?? '';

    $table = '';
    if ($role === 'farmer') {
        $table = 'farmers';
        $insertQuery = "INSERT INTO farmers (name, email, password, phone, location) VALUES (?, ?, ?, ?, ?)";
    } elseif ($role === 'buyer') {
        $table = 'buyers';
        $insertQuery = "INSERT INTO buyers (name, email, password, phone, company) VALUES (?, ?, ?, ?, ?)";
    } elseif ($role === 'insurance_agent') {
        $table = 'insurance_agents';
        $insertQuery = "INSERT INTO insurance_agents (name, email, password, agency, phone) VALUES (?, ?, ?, ?, ?)";
    } elseif ($role === 'admin') {
        $adminCodeSecret = 'AGRICYCLE'; // Replace with your actual secure code
        if ($adminCodeInput !== $adminCodeSecret) {
            $error = "Unauthorized admin registration attempt.";
        } else {
            $table = 'admins';
            $insertQuery = "INSERT INTO admins (email, password) VALUES (?, ?)";
        }
    } else {
        $error = "Invalid role selected!";
    }

    if (!isset($error)) {
        $checkQuery = "SELECT * FROM $table WHERE email=?";
        $stmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $checkResult = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($checkResult) > 0) {
            $error = "Email already exists in $role database!";
        } else {
            $stmt = mysqli_prepare($conn, $insertQuery);

            if ($role === 'farmer') {
                mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $password, $phone, $location);
            } elseif ($role === 'buyer') {
                mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $password, $phone, $company);
            } elseif ($role === 'insurance_agent') {
                mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $password, $agency, $phone);
            } elseif ($role === 'admin') {
                mysqli_stmt_bind_param($stmt, "ss", $email, $password);
            }

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['user_id'] = mysqli_insert_id($conn);
                $_SESSION['role'] = $role;
                header("Location: login.php");
                exit();
            } else {
                $error = "Database error: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | AgriCycle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('../assets/images/signupbg.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .register-container {
            max-width: 500px;
            margin: 80px auto;
            padding: 25px;
            background: rgba(255,255,255,0.95);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }
        #admin-code-field, #extra-fields > div { display: none; }
    </style>
</head>
<body>

<div class="container">
    <div class="register-container">
        <form method="POST">
            <h2 class="text-center mb-4">Create an Account</h2>
            
            <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

            <div class="form-group mb-2">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="form-group mb-2">
                <label>Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="form-group mb-2">
                <label>Role:</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="farmer">Farmer</option>
                    <option value="buyer">Buyer</option>
                    <option value="insurance_agent">Insurance Agent</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="form-group mb-2" id="admin-code-field">
                <label>Admin Code:</label>
                <input type="text" name="admin_code" class="form-control">
            </div>

            <div id="extra-fields">
                <div id="common-fields">
                    <div class="form-group mb-2">
                        <label>Full Name:</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="form-group mb-2">
                        <label>Phone:</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                </div>
                <div class="form-group mb-2" id="farmer-location">
    <label>Location:</label>
    <input type="text" id="location" name="location" class="form-control" readonly>
    <small class="text-muted">Fetching your location...</small>
</div>

                <div class="form-group mb-2" id="buyer-company">
                    <label>Company:</label>
                    <input type="text" name="company" class="form-control">
                </div>
                <div class="form-group mb-2" id="agent-agency">
                    <label>Agency:</label>
                    <input type="text" name="agency" class="form-control">
                </div>

            </div>

            <button type="submit" class="btn btn-success w-100 mt-3">Register</button>
            <a href="../index.php" class="btn btn-secondary w-100 mt-2">Go back to Home</a>
            <p class="mt-3 text-center">Already have an account? <a href="login.php">Login Here</a></p>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(async function (position) {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;

            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`);
                const data = await response.json();
                const address = data.display_name;
                document.getElementById("location").value = address;
            } catch (error) {
                document.getElementById("location").value = "Unable to fetch address.";
            }
        }, function (error) {
            document.getElementById("location").value = "Permission denied or unavailable.";
        });
    } else {
        document.getElementById("location").value = "Geolocation not supported.";
    }
});
    const roleSelect = document.getElementById('role');
    const adminCodeField = document.getElementById('admin-code-field');
    const extraFields = document.getElementById('extra-fields');
    const commonFields = document.getElementById('common-fields');
    const farmerLoc = document.getElementById('farmer-location');
    const buyerComp = document.getElementById('buyer-company');
    const agentAgency = document.getElementById('agent-agency');

    function updateFields() {
        const role = roleSelect.value;

        adminCodeField.style.display = (role === 'admin') ? 'block' : 'none';
        extraFields.style.display = (role === 'admin') ? 'none' : 'block';
        commonFields.style.display = (role !== 'admin') ? 'block' : 'none';

        farmerLoc.style.display = (role === 'farmer') ? 'block' : 'none';
        buyerComp.style.display = (role === 'buyer') ? 'block' : 'none';
        agentAgency.style.display = (role === 'insurance_agent') ? 'block' : 'none';
    }

    roleSelect.addEventListener('change', updateFields);
    document.addEventListener('DOMContentLoaded', updateFields);
</script>

</body>
</html>