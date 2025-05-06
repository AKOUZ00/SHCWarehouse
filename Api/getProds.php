<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ../login.php');
    exit();
}

include('../db.php');

$query = "SELECT * FROM products";
$result = $conn->query($query);

$products = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

foreach ($products as &$product) {
    foreach ($product as &$value) {
        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

echo json_encode($products);


?>
