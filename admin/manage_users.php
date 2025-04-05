<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users | AgriCycle Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/modern-admin.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white shadow-sm rounded mb-4 px-4 py-3">
  <div class="container-fluid">
    <a class="navbar-brand text-success fw-bold" href="#">AgriCycle Admin Panel</a>
    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
      <li class="nav-item me-3">
        <a class="nav-link" href="../dashboard/admin_dashboard.php">
          <i class="fa-solid fa-chart-line me-1"></i> Dashboard
        </a>
      </li>
      <li class="nav-item me-3">
        <a class="nav-link" href="../admin/manage_users.php">
          <i class="fa-solid fa-file-circle-plus me-1"></i> Manage Users
        </a>
      </li>
      <li class="nav-item me-3">
        <a class="nav-link" href="../admin/verify_farmers.php">
          <i class="fa-solid fa-file-shield me-1"></i> Verify Farmers
        </a>
      </li>
      <li class="nav-item me-3">
        <a class="nav-link" href="../admin/verify_buyers.php">
          <i class="fa-solid fa-file-shield me-1"></i> Verify Buyers
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-danger" href="../auth/logout.php">
          <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
        </a>
      </li>
    </ul>
  </div>
</nav>

<div class="container-fluid py-4">
    <h2 class="text-center text-success mb-4">Manage Users - AgriCycle 🌱</h2>

    <div class="filter-box d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div class="form-group mb-2">
            <input type="text" class="form-control" id="searchInput" placeholder="🔍 Search by name, email, phone...">
        </div>
        <div class="form-group mb-2">
            <select class="form-select" id="roleFilter">
                <option value="all">All Roles</option>
                <option value="farmer">Farmer</option>
                <option value="buyer">Buyer</option>
                <option value="insurance_agent">Insurance Agent</option>
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered table-striped align-middle text-center shadow-sm animate__animated animate__fadeIn">
            <thead class="table-success">
                <tr>
                    <th onclick="sortTable(0)">Name ⬍</th>
                    <th onclick="sortTable(1)">Email ⬍</th>
                    <th onclick="sortTable(2)">Role ⬍</th>
                    <th>Phone</th>
                    <th>More Info</th>
                </tr>
            </thead>
            <tbody id="userTable">
                <?php
                function renderUsers($table, $role) {
                    global $conn;
                    $result = mysqli_query($conn, "SELECT * FROM $table");
                    while ($row = mysqli_fetch_assoc($result)) {
                        $phone = $row['phone'] ?? 'N/A';
                        $name = $row['name'] ?? 'Admin';
                        echo "<tr data-role='$role'>
                                <td>$name</td>
                                <td>{$row['email']}</td>
                                <td class='text-capitalize'>$role</td>
                                <td>$phone</td>
                                <td><button class='btn btn-outline-info btn-sm' onclick='showModal(\"$name\", \"{$row['email']}\", \"$role\", \"$phone\")'>View</button></td>
                            </tr>";
                    }
                }
                renderUsers('farmers', 'farmer');
                renderUsers('buyers', 'buyer');
                renderUsers('insurance_agents', 'insurance_agent');
                renderUsers('admins', 'admin');
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content bg-light">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">User Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="modalBodyContent">
        <!-- Filled via JS -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const table = document.getElementById('userTable');

    searchInput.addEventListener('keyup', filterUsers);
    roleFilter.addEventListener('change', filterUsers);

    function filterUsers() {
        const query = searchInput.value.toLowerCase();
        const role = roleFilter.value;
        const rows = table.querySelectorAll('tr');
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            const matchesQuery = text.includes(query);
            const matchesRole = role === 'all' || row.dataset.role === role;
            row.style.display = (matchesQuery && matchesRole) ? '' : 'none';
        });
    }

    function sortTable(col) {
        const rows = Array.from(table.rows);
        const sorted = rows.sort((a, b) => 
            a.cells[col].innerText.localeCompare(b.cells[col].innerText));
        table.innerHTML = '';
        sorted.forEach(row => table.appendChild(row));
    }

    function showModal(name, email, role, phone) {
        const modalContent = `
            <p><strong>Name:</strong> ${name}</p>
            <p><strong>Email:</strong> ${email}</p>
            <p><strong>Role:</strong> ${role}</p>
            <p><strong>Phone:</strong> ${phone}</p>
        `;
        document.getElementById('modalBodyContent').innerHTML = modalContent;
        const modal = new bootstrap.Modal(document.getElementById('userModal'));
        modal.show();
    }
</script>
</body>
</html>
