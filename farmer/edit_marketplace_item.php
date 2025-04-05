<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = mysqli_query($conn, "SELECT * FROM marketplace_items WHERE id = '$id'");
    $item = mysqli_fetch_assoc($query);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $contact_info = $_POST['contact_info'];

    $sql = "UPDATE marketplace_items SET 
            item_name='$item_name', description='$description', price='$price', 
            quantity='$quantity', contact_info='$contact_info' WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Listing updated successfully!'); window.location.href='marketplace.php';</script>";
        exit();
    } else {
        echo "Error updating listing: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Listing - Marketplace</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin: 10px 0 5px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        textarea {
            resize: none;
            height: 80px;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        button {
            width: 48%;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .update-btn {
            background-color: #28a745;
            color: white;
        }

        .update-btn:hover {
            background-color: #218838;
        }

        .cancel-btn {
            background-color: #dc3545;
            color: white;
        }

        .cancel-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Listing</h2>
        <form method="POST">
            <label>Item Name</label>
            <input type="text" name="item_name" value="<?= htmlspecialchars($item['item_name']) ?>" required>

            <label>Description</label>
            <textarea name="description" required><?= htmlspecialchars($item['description']) ?></textarea>

            <label>Price per kg/unit</label>
            <input type="number" name="price" value="<?= htmlspecialchars($item['price']) ?>" required>

            <label>Quantity Available</label>
            <input type="number" name="quantity" value="<?= htmlspecialchars($item['quantity']) ?>" required>

            <label>Contact Info</label>
            <input type="text" name="contact_info" value="<?= htmlspecialchars($item['contact_info']) ?>" required>

            <div class="button-group">
                <button type="submit" class="update-btn">Update</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='marketplace.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>
