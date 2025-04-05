<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'farmer') {
    die("Access Denied. Only farmers can add items.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $contact_info = mysqli_real_escape_string($conn, $_POST['contact_info']);
    $seller_id = $_SESSION['user_id'];

    // File upload logic
    $photo_path = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['photo']['tmp_name'];
        $fileName = $_FILES['photo']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($fileExtension, $allowedExts)) {
            $newFileName = uniqid("waste_") . '.' . $fileExtension;
            $uploadFileDir = '../uploads/wasteimg/';

            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0777, true);
            }

            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $photo_path = 'uploads/wasteimg/' . $newFileName;
            } else {
                echo "❌ Error moving the uploaded file.";
                exit();
            }
        } else {
            echo "❌ Invalid file type. Only JPG, PNG, or WEBP allowed.";
            exit();
        }
    }

    $query = "INSERT INTO marketplace_items (item_name, description, price, quantity, contact_info, user_id, photo_path) 
              VALUES ('$item_name', '$description', '$price', '$quantity', '$contact_info', '$seller_id', '$photo_path')";

    if (mysqli_query($conn, $query)) {
        header("Location: ../marketplace/index.php"); // redirect to marketplace after successful insert
        exit();
    } else {
        echo "❌ Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Item | AgriCycle</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<?php include '../marketplace/navbar.php'; ?>

<div class="container mt-4">
    <h2 class="text-center text-success">Add Waste Item</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Item Name</label>
            <input type="text" name="item_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Price (₹)</label>
            <input type="number" name="price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Contact Information</label>
            <input type="text" name="contact_info" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload Waste Photo (JPG, PNG, WEBP)</label>
            <input type="file" name="photo" class="form-control" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-success">Add Item</button>
    </form>
</div>

</body>
</html>
