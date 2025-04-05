<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer') {
    header("Location: ../auth/login.php");
    exit();
}

include '../config/db_connect.php';

$farmer_id = $_SESSION['user_id'];

$query = "
    SELECT 
        pr.status, pr.applied_at,
        p.name,
        a.agency AS agency_name
    FROM policy_requests pr
    JOIN bank_policies p ON pr.policy_id = p.id
    JOIN insurance_agents a ON pr.agent_id = a.id
    WHERE pr.farmer_id = ?
";

$stmt = mysqli_prepare($conn, $query);
if (!$stmt) {
    die("Query preparation failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, 'i', $farmer_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Policy Application Status | AgriCycle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Fonts & Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e0f7ec, #c4e3e6);
            color: #333;
            margin: 0;
            padding: 0;
            animation: fadeIn 0.8s ease-in-out;
        }

        .container {
            margin-top: 60px;
            background: #fff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            animation: slideUp 0.6s ease;
        }

        h2 {
            font-weight: 600;
            color: #00695c;
            margin-bottom: 25px;
            text-align: center;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            padding: 12px;
            border-radius: 10px;
            font-weight: 500;
            background-color: #e6ffe9;
            border: 1px solid #b2dfdb;
            color: #00695c;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        thead tr {
            background-color: #00695c;
            color: white;
        }

        th, td {
            padding: 14px;
            text-align: center;
        }

        tbody tr {
            background-color: #f5f5f5;
            transition: all 0.3s ease;
            border-radius: 10px;
        }

        tbody tr:hover {
            transform: scale(1.02);
            background-color: #e0f2f1;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.97); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📜 Your Applied Policies</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="message"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Policy Name</th>
                    <th>Agency Name</th>
                    <th>Status</th>
                    <th>Applied At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['agency_name']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td><?= htmlspecialchars($row['applied_at']) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
