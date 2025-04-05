<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'buyer') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = $_POST['item_id'];
    $quantity = (int)$_POST['quantity'];
    $total_cost = $_POST['total_cost'];
    $buyer_id = $_SESSION['user_id'];

    // Fetch item
    $query = "SELECT * FROM marketplace_items WHERE id = '$item_id'";
    $result = mysqli_query($conn, $query);
    $item = mysqli_fetch_assoc($result);

    if (!$item) {
        die("Item not found.");
    }

    if ($quantity > $item['quantity']) {
        echo "<script>alert('You cannot buy more than the available quantity.'); window.location.href='buy_item.php?id=$item_id';</script>";
        exit();
    }

    // Update stock
    $new_quantity = $item['quantity'] - $quantity;
    mysqli_query($conn, "UPDATE marketplace_items SET quantity = $new_quantity WHERE id = $item_id");

    // Insert into orders table
    $insert_query = "INSERT INTO orders (buyer_id, item_id, quantity, total_price)
                     VALUES ('$buyer_id', '$item_id', '$quantity', '$total_cost')";
    mysqli_query($conn, $insert_query);

    // Generate invoice text
    $invoice = "✅ Purchase Confirmed!\n\n";
    $invoice .= "Item: " . $item['item_name'] . "\n";
    $invoice .= "Description: " . $item['description'] . "\n";
    $invoice .= "Unit Price: ₹" . $item['price'] . "\n";
    $invoice .= "Quantity: " . $quantity . "\n";
    $invoice .= "Total Cost: ₹" . $total_cost . "\n";
    $invoice .= "Seller Contact: " . $item['contact_info'] . "\n";
    $invoice .= "Date: " . date("Y-m-d H:i");

    echo "<h3 style='text-align:center; color:green;'>Purchase Invoice</h3>";
    echo "<pre style='margin: 20px auto; padding: 15px; max-width: 600px; border: 1px solid #ccc; background-color: #f9f9f9; font-size: 16px;'>$invoice</pre>";
    echo "<div style='text-align:center;'><a href='../dashboard/buyer_dashboard.php' style='text-decoration:none; padding:10px 20px; background:green; color:white; border-radius:5px;'>Back to Home</a></div>";
}
?>
