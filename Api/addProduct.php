<?php
session_start();
if (!isset($_SESSION['email']) || !in_array($_SESSION['email'], ['youssefa@saharahomecare.com', 'armaghan@saharahomecare.com', 'portalinquiries@saharahomecare.com'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit();
}

include('../db.php');

// Check if form data is posted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $section = $_POST['section'];
    $item_number = $_POST['item_number'];
    $product_link = $_POST['product_link'];

    // Handle image upload
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image_name = basename($_FILES['image']['name']);
        $target_dir = "../img/";
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_url = "/img/" . $image_name; // Save relative image path to database
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to upload image']);
            exit();
        }
    }

    // Insert the new product into the database
    $stmt = $conn->prepare("INSERT INTO products (name, section, image_url, product_link, item_number) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $section, $image_url, $product_link, $item_number);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Product added successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add product']);
    }

    $stmt->close();
    $conn->close();
}
?>
