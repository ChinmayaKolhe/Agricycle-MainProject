<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer') {
    header("Location: ../auth/login.php");
    exit();
}

include '../config/db_connect.php';
include '../farmer/header.php';

$farmer_id = $_SESSION['user_id'];

// Fetch all policies with agent's agency name
$query = "SELECT bp.id, bp.name, bp.pdf_path, bp.bank_link, bp.agent_id, ia.agency AS agent_name 
          FROM bank_policies bp
          JOIN insurance_agents ia ON bp.agent_id = ia.id";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Government Schemes | AgriCycle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2 class="text-primary">Government Schemes & Policies</h2>
    <p>Explore various policies and apply directly.</p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Policy Name</th>
                <th>Agency</th>
                <th>PDF</th>
                <th>Bank Link</th>
                <th>Apply</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['agent_name']) ?></td>
                    <td>
                    <?php if ($row['pdf_path']) { ?>
                        <a href="download_pdf.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-info">Download</a>
                        <?php } else { echo "N/A"; } ?>
                    </td>
                    <td>
                        <?php if ($row['bank_link']) { ?>
                            <a href="<?= htmlspecialchars($row['bank_link']) ?>" target="_blank" class="btn btn-sm btn-outline-success">Apply</a>
                        <?php } else { echo "N/A"; } ?>
                    </td>
                    
                    <td>
                    <form action="apply_policy.php" method="POST">
                        <input type="hidden" name="policy_id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="agent_id" value="<?= $row['agent_id'] ?>"> <!-- ADD THIS -->
                        <button type="submit" class="btn btn-sm btn-primary">Apply Now</button>
                        </form>

                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
