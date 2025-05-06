<?php
// shopping-list.php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}
include('db.php');

// Check if order ID is provided
if (!isset($_GET['order_id'])) {
    echo "Order ID is required.";
    exit;
}

$order_id = intval($_GET['order_id']);

// Get the order details
$order_query = "SELECT * FROM orders WHERE id = ?";
$stmt_order = $conn->prepare($order_query);
$stmt_order->bind_param("i", $order_id);
$stmt_order->execute();
$order_result = $stmt_order->get_result();

if ($order_result->num_rows === 0) {
    echo "Order not found.";
    exit;
}

$order = $order_result->fetch_assoc();

// Get the order items with product details
$items_query = "
    SELECT oi.*, p.name as product_name, p.image_url, p.product_link, p.item_number
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?";
$stmt_items = $conn->prepare($items_query);
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$items_result = $stmt_items->get_result();

$orderItems = [];
while ($item = $items_result->fetch_assoc()) {
    $orderItems[] = $item;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping List - Order #<?php echo $order_id; ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="https://saharahomecare.com/wp-content/uploads/2024/03/cropped-shc-favicon-32x32.png" sizes="32x32" />
    <style>
        .item-done {
            text-decoration: line-through;
            opacity: 0.6;
        }
        .product-card {
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        .product-card.checked {
            background-color: #f8f9fa;
        }
        .check-container {
            position: relative;
            padding-left: 35px;
            cursor: pointer;
            font-size: 18px;
            user-select: none;
            display: block;
            margin-top: 10px;
        }
        .check-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: #eee;
            border-radius: 4px;
        }
        .check-container:hover input ~ .checkmark {
            background-color: #ccc;
        }
        .check-container input:checked ~ .checkmark {
            background-color: #28a745;
        }
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }
        .check-container input:checked ~ .checkmark:after {
            display: block;
        }
        .check-container .checkmark:after {
            left: 9px;
            top: 5px;
            width: 7px;
            height: 12px;
            border: solid white;
            border-width: 0 3px 3px 0;
            transform: rotate(45deg);
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-12">
                <h1>Shopping List - Order #<?php echo $order_id; ?></h1>
                <div class="alert alert-info">
                    <strong>From:</strong> <?php echo htmlspecialchars($order['name']); ?> (<?php echo htmlspecialchars($order['email']); ?>)<br>
                    <strong>Location:</strong> <?php echo htmlspecialchars($order['location']); ?><br>
                    <strong>Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?><br>
                    <strong>Status:</strong> <span class="badge badge-<?php echo $order['Status'] == 'Pending' ? 'warning' : 'success'; ?>"><?php echo htmlspecialchars($order['Status']); ?></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h3>Items to Purchase:</h3>
                <p>Check off items as you purchase them. Click "Complete Order" when all items have been purchased.</p>
            </div>
        </div>

        <form id="shoppingListForm">
            <div class="row" id="productGrid">
                <?php foreach ($orderItems as $item): ?>
                <div class="col-md-4">
                    <div class="card product-card" data-item-id="<?php echo $item['id']; ?>">
                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($item['product_name']); ?></h5>
                            <p class="card-text">
                                Quantity: <strong><?php echo htmlspecialchars($item['quantity']); ?></strong><br>
                                Item #: <strong><a href="<?php echo htmlspecialchars($item['product_link']); ?>" target="_blank"><?php echo htmlspecialchars($item['item_number']); ?></a></strong>
                            </p>
                            <a href="<?php echo htmlspecialchars($item['product_link']); ?>" target="_blank" class="btn btn-primary btn-sm">View on Sam's Club</a>
                            
                            <label class="check-container">Mark as purchased
                                <input type="checkbox" class="item-checkbox" name="items[]" value="<?php echo $item['id']; ?>">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="row mt-4 mb-5">
                <div class="col-12 text-center">
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                    <button type="button" id="completeOrderBtn" class="btn btn-success btn-lg hidden">Complete Order</button>
                </div>
            </div>
        </form>

        <!-- Confirmation Modal -->
        <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmationModalLabel">Confirm Order Completion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure all items have been purchased and the order is complete?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" id="confirmComplete">Yes, Complete Order</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="successModalLabel">Order Completed</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        The order has been marked as completed successfully!
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            const totalItems = <?php echo count($orderItems); ?>;
            let checkedItems = 0;

            // Handle checkbox changes
            $('.item-checkbox').on('change', function() {
                const card = $(this).closest('.product-card');
                
                if ($(this).is(':checked')) {
                    card.addClass('checked');
                    checkedItems++;
                } else {
                    card.removeClass('checked');
                    checkedItems--;
                }
                
                // Show or hide the complete order button
                if (checkedItems === totalItems) {
                    $('#completeOrderBtn').removeClass('hidden');
                } else {
                    $('#completeOrderBtn').addClass('hidden');
                }
            });

            // Complete order button click
            $('#completeOrderBtn').on('click', function() {
                $('#confirmationModal').modal('show');
            });

            // Confirm completion
            $('#confirmComplete').on('click', function() {
                const orderId = <?php echo $order_id; ?>;
                
                // Send AJAX request to update order status
                $.ajax({
                    url: 'Api/updateOrderStatus.php',
                    method: 'POST',
                    data: {
                        id: orderId,
                        Status: 'Completed'
                    },
                    success: function(response) {
                        $('#confirmationModal').modal('hide');
                        $('#successModal').modal('show');
                        
                        // Update status badge on page
                        $('.badge').removeClass('badge-warning').addClass('badge-success').text('Completed');
                    },
                    error: function() {
                        alert('Error updating order status. Please try again.');
                    }
                });
            });

            // Close success modal and redirect to home
            $('#successModal').on('hidden.bs.modal', function() {
                window.location.href = 'index.php';
            });
        });
    </script>
</body>
</html>