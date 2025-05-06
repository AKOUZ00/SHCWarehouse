<?php
include('../db.php');

// Query to get top 5 most ordered products by total quantity ordered
$query = "
    SELECT p.name, p.image_url, p.product_link, p.item_number, SUM(oi.quantity) AS total_quantity
    FROM products p
    JOIN order_items oi ON p.id = oi.product_id
    GROUP BY p.id
    ORDER BY total_quantity DESC
    LIMIT 5";

$result = $conn->query($query);
$topProducts = [];

// Fetch the results into an array
while ($row = $result->fetch_assoc()) {
    $topProducts[] = $row;
}

// Return as JSON
echo json_encode($topProducts);

// Close the database connection if needed
$conn->close();
?>
