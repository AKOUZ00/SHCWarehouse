<?php
include('../db.php');

// Set Content-Type header for JSON response
header('Content-Type: application/json');

$query = "SELECT COUNT(*) AS total_products FROM products";
$result = $conn->query($query);

// Check if query execution was successful
if ($result) {
    $totalProducts = $result->fetch_assoc();
    echo json_encode($totalProducts);
} else {
    // Return an error response if query failed
    echo json_encode(["error" => "Failed to execute query"]);
}

// Close the database connection if needed
$conn->close();
?>
