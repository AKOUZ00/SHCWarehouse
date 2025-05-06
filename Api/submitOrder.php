<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ../login.php');
    exit();
}

include('../db.php');

$name = $_POST['name'];
$email = $_POST['email'];
$location = $_POST['location'];
$order = json_decode($_POST['order'], true);  // Decoded array of order items

// Get today's date
$today = date('Y-m-d');

// Set the default status to "Pending"
$status = "Pending";

// Insert the order into the orders table
$stmt = $conn->prepare("INSERT INTO orders (name, email, location, order_date, Status) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $email, $location, $today, $status);

if ($stmt->execute()) {
    // Get the last inserted order_id
    $order_id = $stmt->insert_id;

    // Prepare statement to insert order items into the order_items table
    $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");

    // Start building the email order details with a table structure
    $email_order_details = "
        <table border='1' cellpadding='10' cellspacing='0'>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Item Number</th>
                    <th>Product Link</th>
                </tr>
            </thead>
            <tbody>";

    // Loop through each order item and insert into the database
    foreach ($order as $item) {
        $item_number = $item['itemNumber'];  // Get the item number from the order

        // Query the products table to get the product ID using the item number
        $query = "SELECT id FROM products WHERE item_number = ?";
        $stmt_product = $conn->prepare($query);
        $stmt_product->bind_param("s", $item_number);
        $stmt_product->execute();
        $stmt_product->bind_result($product_id);
        $stmt_product->fetch();
        $stmt_product->close();

        if ($product_id) {
            $quantity = $item['quantity'];

            // Insert into order_items table
            $stmt_item->bind_param("iii", $order_id, $product_id, $quantity);
            $stmt_item->execute();

            // Append item details to the email content
            $item_name = $item['name'];
            $product_link = $item['link'];

            // Build the HTML table row for the email
            $email_order_details .= "
                <tr>
                    <td>$item_name</td>
                    <td>$quantity</td>
                    <td>$item_number</td>
                    <td><a href='$product_link' target='_blank'>View Product</a></td>
                </tr>";
        } else {
            // If the product is not found in the products table, log an error or take action
            error_log("Product with item number $item_number not found.");
        }
    }

    $email_order_details .= "
        </tbody>
    </table>";

    // Create the shopping list URL
    $shopping_list_url = "https://" . $_SERVER['HTTP_HOST'] . "/shopping-list.php?order_id=" . $order_id;

    // Email recipients
    $to = "armaghan@saharahomecare.com, fahadk@saharahomecare.com, $email";

    // Email subject and message
    $subject = "Order Request from $location - $today";
    $message = "
        <html>
        <head>
            <title>Order Request from $location</title>
        </head>
        <body>
            <h2>Order Request from $location</h2>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Location:</strong> $location</p>
            <p><strong>Order Details:</strong></p>
            $email_order_details
            <br>
            <p><a href='$shopping_list_url' style='display: inline-block; background-color: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;'>View Shopping List</a></p>
            <p><strong>Click the button above to access the shopping list and mark items as purchased.</strong></p>
            <br><br>
            <p><strong>Thank you for your order!</strong> We appreciate your trust in us to fulfill your office supply needs. If you have any further questions or need assistance, feel free to reach out at <a href='mailto:portalinquiries@saharahomecare.com'>portalinquiries@saharahomecare.com</a>. We look forward to serving you again!</p>
            <p>Best regards,<br>The Sahara Home Care Team</p>
        </body>
        </html>
    ";

    // Headers for HTML email
    $headers = "From: noreply@saharahomecare.store" . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    $headers .= "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";

    // Send the email
    if (mail($to, $subject, $message, $headers)) {
        // If email is sent successfully, redirect to thank-you page
        $_SESSION['order'] = $order;
        $_SESSION['name'] = $name;
        $_SESSION['location'] = $location;
        $_SESSION['email'] = $email;
        header("Location: ../thank-you.html");
        exit();
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Failed to send email.'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Failed to save order.'));
}
?>