<?php
session_start();
if (!isset($_SESSION['email']) || !in_array($_SESSION['email'], ['youssefa@saharahomecare.com', 'armaghan@saharahomecare.com', 'portalinquiries@saharahomecare.com'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit();
}

include('../db.php');

// Check if form data is posted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Delete product from the database
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Product deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete product']);
    }

    $stmt->close();
    $conn->close();
}
?>
