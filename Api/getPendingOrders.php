<?php
include('../db.php');

// Set Content-Type header for JSON response
header('Content-Type: application/json');

// Query to count only the orders with "pending" status
$query = "SELECT COUNT(*) AS total_pending_orders FROM orders WHERE Status = 'Pending'";
$result = $conn->query($query);

// Check if query execution was successful
if ($result) {
    $totalPendingOrders = $result->fetch_assoc();
    echo json_encode($totalPendingOrders);
} else {
    // Return an error response if query failed
    echo json_encode(["error" => "Failed to execute query"]);
}

// Close the database connection if needed
$conn->close();
?>
