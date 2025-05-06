<?php
// getUserDashboardData.php - To retrieve dashboard data based on user role
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ../login.php');
    exit();
}

include('../db.php');

// Define admin emails
$adminEmails = ['youssefa@saharahomecare.com', 'armaghan@saharahomecare.com', 'fahadk@saharahomecare.com'];
$isAdmin = in_array($_SESSION['email'], $adminEmails);

// Get user-specific or all data based on role
$response = [
    'total_orders' => 0,
    'pending_orders' => 0,
    'total_products' => 0,
    'recent_orders' => [],
    'top_products' => [],
    'is_admin' => $isAdmin
];

// Get total orders
if ($isAdmin) {
    // For admins, get all orders
    $query = "SELECT COUNT(*) as count FROM orders";
    $stmt = $conn->prepare($query);
} else {
    // For regular users, get only their orders
    $query = "SELECT COUNT(*) as count FROM orders WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $_SESSION['email']);
}

$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $response['total_orders'] = $row['count'];
}

// Get pending orders
if ($isAdmin) {
    // For admins, get all pending orders
    $query = "SELECT COUNT(*) as count FROM orders WHERE Status = 'Pending'";
    $stmt = $conn->prepare($query);
} else {
    // For regular users, get only their pending orders
    $query = "SELECT COUNT(*) as count FROM orders WHERE email = ? AND Status = 'Pending'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $_SESSION['email']);
}

$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $response['pending_orders'] = $row['count'];
}

// Get total products
$query = "SELECT COUNT(*) as count FROM products";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $response['total_products'] = $row['count'];
}

// Get recent orders
if ($isAdmin) {
    // For admins, get all recent orders
    $query = "SELECT o.id as order_id, o.name, o.email, o.location, o.order_date, o.Status
              FROM orders o
              ORDER BY o.order_date DESC LIMIT 5";
    $stmt = $conn->prepare($query);
} else {
    // For regular users, get only their recent orders
    $query = "SELECT o.id as order_id, o.name, o.email, o.location, o.order_date, o.Status
              FROM orders o
              WHERE o.email = ?
              ORDER BY o.order_date DESC LIMIT 5";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $_SESSION['email']);
}

$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $response['recent_orders'][] = $row;
}

// Get top products
if ($isAdmin) {
    // For admins, get overall top products
    $query = "SELECT p.id, p.name, p.image_url, p.product_link, p.item_number, 
              SUM(oi.quantity) as total_quantity
              FROM products p
              JOIN order_items oi ON p.id = oi.product_id
              GROUP BY p.id
              ORDER BY total_quantity DESC LIMIT 5";
    $stmt = $conn->prepare($query);
} else {
    // For regular users, get their most ordered products
    $query = "SELECT p.id, p.name, p.image_url, p.product_link, p.item_number, 
              SUM(oi.quantity) as total_quantity
              FROM products p
              JOIN order_items oi ON p.id = oi.product_id
              JOIN orders o ON oi.order_id = o.id
              WHERE o.email = ?
              GROUP BY p.id
              ORDER BY total_quantity DESC LIMIT 5";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $_SESSION['email']);
}

$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $response['top_products'][] = $row;
}

// Additional statistics for admin users
if ($isAdmin) {
    // Get orders by location
    $query = "SELECT location, COUNT(*) as count FROM orders GROUP BY location ORDER BY count DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $response['location_stats'] = [];
    while ($row = $result->fetch_assoc()) {
        $response['location_stats'][] = $row;
    }
    
    // Get orders by status
    $query = "SELECT Status, COUNT(*) as count FROM orders GROUP BY Status";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $response['status_stats'] = [];
    while ($row = $result->fetch_assoc()) {
        $response['status_stats'][] = $row;
    }
    
    // Get monthly orders for the current year
    $currentYear = date('Y');
    $query = "SELECT MONTH(order_date) as month, COUNT(*) as count 
              FROM orders 
              WHERE YEAR(order_date) = ? 
              GROUP BY MONTH(order_date) 
              ORDER BY month";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $currentYear);
    $stmt->execute();
    $result = $stmt->get_result();
    $response['monthly_stats'] = [];
    while ($row = $result->fetch_assoc()) {
        $response['monthly_stats'][] = $row;
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
$conn->close();