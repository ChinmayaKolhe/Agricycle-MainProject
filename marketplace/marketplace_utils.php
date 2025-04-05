<?php
include '../config/db_connect.php';

function fetchMarketplaceItems() {
    global $conn;
    $sql = "SELECT * FROM marketplace_items ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);

    $items = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }
    return $items;
}
?>
