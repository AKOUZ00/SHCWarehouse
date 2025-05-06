<?php
// Api/updateOrderStatus.php

session_start();
include('../db.php');

// Ensure there's a valid session
if (!isset($_SESSION['email'])) {
    echo json_encode(array('status' => 'error', 'message' => 'User not logged in.'));
    exit();
}

// Check if required parameters are provided
if (isset($_POST['id']) && isset($_POST['Status'])) {
    $orderId = intval($_POST['id']);
    $newStatus = $_POST['Status'];
    
    // Log the received data for debugging
    error_log("Received Order ID: " . $orderId);
    error_log("Received Status: " . $newStatus);
    
    // Validate the status value (only allow Pending or Completed)
    if ($newStatus !== 'Pending' && $newStatus !== 'Completed') {
        echo json_encode(array('status' => 'error', 'message' => 'Invalid status value.'));
        exit();
    }
    
    // Prepare the query to update the order Status
    $query = "UPDATE orders SET Status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $newStatus, $orderId);
    
    // Execute the query
    if ($stmt->execute()) {
        // If order is marked as completed, send confirmation email
        if ($newStatus === 'Completed') {
            // Get order details for the email
            $orderQuery = "SELECT * FROM orders WHERE id = ?";
            $orderStmt = $conn->prepare($orderQuery);
            $orderStmt->bind_param("i", $orderId);
            $orderStmt->execute();
            $orderResult = $orderStmt->get_result();
            $order = $orderResult->fetch_assoc();
            
            // Send confirmation email
            $to = $order['email'];
            $subject = "Your Order Has Been Completed - Order #$orderId";
            $message = "
                <html>
                <head>
                    <title>Order Completed - #$orderId</title>
                </head>
                <body>
                    <h2>Your Order Has Been Completed</h2>
                    <p>Dear {$order['name']},</p>
                    <p>We're pleased to inform you that your order #$orderId has been completed and all items have been purchased.</p>
                    <p>The supplies will be delivered to your location ({$order['location']}) soon.</p>
                    <p>If you have any questions, please contact us at <a href='mailto:portalinquiries@saharahomecare.com'>portalinquiries@saharahomecare.com</a>.</p>
                    <p>Thank you for using the Sahara Home Care Office Supply Portal!</p>
                    <p>Best regards,<br>The Sahara Home Care Team</p>
                </body>
                </html>
            ";
            
            // Headers for HTML email
            $headers = "From: noreply@saharahomecare.store" . "\r\n";
            $headers .= "Reply-To: portalinquiries@saharahomecare.com" . "\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();
            $headers .= "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
            
            // Send email
            mail($to, $subject, $message, $headers);
        }
        
        echo json_encode(array('status' => 'success'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Failed to update the database.'));
    }
    
    $stmt->close();
    $conn->close();
} else {
    // Log invalid input for debugging
    error_log("Invalid input: " . print_r($_POST, true));
    echo json_encode(array('status' => 'error', 'message' => 'Invalid input.'));
}
?>