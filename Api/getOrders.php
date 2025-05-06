<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}
include('../db.php');

// Ensure the session email is set
if (!isset($_SESSION['email'])) {
    echo json_encode(array('status' => 'error', 'message' => 'User not logged in.'));
    exit();
}

// List of allowed admin emails
$adminEmails = ['portalinquiries@saharahomecare.com', 'armaghan@saharahomecare.com', 'youssefa@saharahomecare.com', 'fahadk@saharahomecare.com'];

// Check if the logged-in user is one of the admin users
if (in_array($_SESSION['email'], $adminEmails)) {
    // Fetch all orders with their items and product details
    $query = "
        SELECT o.id as order_id, o.name, o.email, o.location, o.order_date, o.Status, 
               oi.product_id, oi.quantity, 
               p.name as product_name, p.image_url, p.product_link, p.item_number
        FROM orders o
        LEFT JOIN order_items oi ON o.id = oi.order_id
        LEFT JOIN products p ON oi.product_id = p.id
        ORDER BY o.order_date DESC";
} else {
    // Fetch only orders for the logged-in user with their items and product details
    $query = "
        SELECT o.id as order_id, o.name, o.email, o.location, o.order_date, o.Status, 
               oi.product_id, oi.quantity, 
               p.name as product_name, p.image_url, p.product_link, p.item_number
        FROM orders o
        LEFT JOIN order_items oi ON o.id = oi.order_id
        LEFT JOIN products p ON oi.product_id = p.id
        WHERE o.email = ?
        ORDER BY o.order_date DESC";
}



if (in_array($_SESSION['email'], $adminEmails)) {
    $stmt = $conn->prepare($query);
} else {
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $_SESSION['email']); // Bind email for regular users
}

$stmt->execute();
$result = $stmt->get_result();

// Prepare the orders data with products
$orders = array();
while ($row = $result->fetch_assoc()) {
    // Grouping products by order_id
    if (!isset($orders[$row['order_id']])) {
        $orders[$row['order_id']] = array(
            'order_id' => $row['order_id'],
            'name' => $row['name'],
            'email' => $row['email'],
            'location' => $row['location'],
            'order_date' => $row['order_date'],
            'Status' => $row['Status'],
            'products' => array()
        );
    }

    // Add product details to each order
    $orders[$row['order_id']]['products'][] = array(
        'product_name' => $row['product_name'],
        'quantity' => $row['quantity'],
        'image_url' => $row['image_url'],
        'product_link' => $row['product_link'],
        'item_number' => $row['item_number']
    );
}

// Return the orders as JSON
header('Content-Type: application/json');
echo json_encode(array_values($orders));
?>
