<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'farmer') {
    die("Access Denied.");
}

if (!isset($_GET['id'])) {
    die("Item not found.");
}

$item_id = intval($_GET['id']);
$seller_id = $_SESSION['user_id'];

$query = "SELECT * FROM marketplace_items WHERE id = '$item_id' AND user_id = '$seller_id'";
$result = mysqli_query($conn, $query);
$item = mysqli_fetch_assoc($result);

if (!$item) {
    die("Item not found or you don't have permission.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $contact_info = mysqli_real_escape_string($conn, $_POST['contact_info']);

    $update_query = "UPDATE marketplace_items SET 
        item_name = '$item_name', 
        description = '$description', 
        price = '$price', 
        quantity = '$quantity', 
        contact_info = '$contact_info' 
        WHERE id = '$item_id' AND user_id = '$seller_id'";

    if (mysqli_query($conn, $update_query)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating item: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Item | AgriCycle</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<?php include '../marketplace/navbar.php'; ?>

<div class="container mt-4">
    <h2 class="text-center text-success">Edit Item</h2>
    <form method="POST">
        <input type="text" name="item_name" value="<?= htmlspecialchars($item['item_name']) ?>" required>
        <input type="number" name="price" value="<?= htmlspecialchars($item['price']) ?>" required>
        <input type="text" name="contact_info" value="<?= htmlspecialchars($item['contact_info']) ?>" required>
        <button type="submit" class="btn btn-warning">Update Item</button>
    </form>
</div>

</body>
</html>
