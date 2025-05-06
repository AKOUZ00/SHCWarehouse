<?php
include('../db.php');

$productId = $_POST['productId'];
$productName = $_POST['productName'];
$productSection = $_POST['productSection'];
$itemNumber = $_POST['itemNumber'];
$productLink = $_POST['productLink'];

// Ensure product ID is available
if (!$productId) {
    echo json_encode(['status' => 'error', 'message' => 'Product ID is missing']);
    exit();
}

// Prepare the SQL update statement
$query = "UPDATE products SET name = ?, section = ?, item_number = ?, product_link = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssssi", $productName, $productSection, $itemNumber, $productLink, $productId);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Product updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update product']);
}

$stmt->close();
$conn->close();
?>
