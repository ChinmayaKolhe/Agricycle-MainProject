<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'buyer') {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit();
}

$item_id = mysqli_real_escape_string($conn, $_GET['id']);

$query = "SELECT * FROM marketplace_items WHERE id = '$item_id'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Item not found.";
    exit();
}

$item = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Buy Now - <?= htmlspecialchars($item['item_name']) ?> | AgriCycle</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="card shadow p-4">
        <h3 class="text-success">Buy Now - <?= htmlspecialchars($item['item_name']) ?></h3>
        <hr>
        <form action="place_order.php" method="POST">
            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
            <input type="hidden" id="price" value="<?= $item['price'] ?>">

            <p><strong>Description:</strong><br> <?= htmlspecialchars($item['description']) ?></p>
            <p><strong>Available Quantity:</strong> <?= $item['quantity'] ?></p>

            <div class="mb-3">
                <label for="quantity" class="form-label"><strong>Select Quantity:</strong></label>
                <input type="number" class="form-control" id="quantity" name="quantity"
                       min="1" max="<?= $item['quantity'] ?>" value="1" required
                       oninput="updateTotal()">
            </div>

            <div class="mb-3">
                <label for="total_cost" class="form-label"><strong>Total Cost (₹):</strong></label>
                <input type="text" class="form-control" id="total_cost" name="total_cost" readonly>
            </div>

            <p><strong>Seller Contact Info:</strong><br> <?= htmlspecialchars($item['contact_info']) ?></p>

            <button type="submit" class="btn btn-success">Confirm Purchase</button>
            <a href="marketplace.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<script>
    let debounceTimer;

    function updateTotal() {
        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(() => {
            const price = parseFloat(document.getElementById('price').value);
            const quantityInput = document.getElementById('quantity');
            const totalField = document.getElementById('total_cost');
            const maxQuantity = parseInt(quantityInput.getAttribute('max'));
            let quantity = parseInt(quantityInput.value);

            if (isNaN(quantity)) {
                totalField.value = "0.00";
                return;
            }

            if (quantity < 1) {
                quantity = 1;
            }

            if (quantity > maxQuantity) {
                alert("Selected quantity exceeds available stock!");
                quantity = maxQuantity;
            }

            quantityInput.value = quantity;
            totalField.value = (price * quantity).toFixed(2);
        }, 400);
    }

    window.addEventListener("DOMContentLoaded", updateTotal);
</script>
</body>
</html>
