<?php
include('../db.php');

session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ../login.php');
    exit();
}

// Get the order ID from the request
$order_id = isset($_POST['order_id']) ? $_POST['order_id'] : '';

if (!empty($order_id)) {
    // Start a transaction
    $conn->begin_transaction();

    try {
        // Prepare the delete statement for order items
        $stmt_items = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
        if ($stmt_items === false) {
            throw new Exception('Failed to prepare statement for order items deletion.');
        }
        $stmt_items->bind_param("i", $order_id);
        $stmt_items->execute();

        // Prepare the delete statement for the order
        $stmt_order = $conn->prepare("DELETE FROM orders WHERE id = ?");
        if ($stmt_order === false) {
            throw new Exception('Failed to prepare statement for order deletion.');
        }
        $stmt_order->bind_param("i", $order_id);
        $stmt_order->execute();

        // Check if the order was deleted
        if ($stmt_order->affected_rows > 0) {
            // Commit the transaction
            $conn->commit();
            echo json_encode(array('status' => 'success'));
        } else {
            // Rollback if no order was deleted
            $conn->rollback();
            echo json_encode(array('status' => 'error', 'message' => 'No order found with the given ID.'));
        }

        // Close the statements
        $stmt_items->close();
        $stmt_order->close();

    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $conn->rollback();
        echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid order ID.'));
}

// Close the connection
$conn->close();
?>
