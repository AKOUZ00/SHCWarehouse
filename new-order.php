<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Office Supply Request Form</title>
    <meta name="theme-color" content="#09a223">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Header -->
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">New Order</h1>
            <p class="text-gray-600">Request office supplies for your location</p>
        </header>

        <form id="orderForm" class="bg-white rounded-lg shadow-md p-6">
            <!-- User Information Section -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-700 mb-4 pb-2 border-b">User Information</h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" class="w-full px-4 py-2 bg-gray-100 rounded border border-gray-300" 
                               id="name" value="<?php echo $_SESSION['name'];?>" disabled required>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Work Email</label>
                        <input type="email" class="w-full px-4 py-2 bg-gray-100 rounded border border-gray-300" 
                               id="email" value="<?php echo $_SESSION['email'];?>" disabled required>
                    </div>
                </div>
                <div class="mt-4">
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <select class="w-full px-4 py-2 rounded border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200" 
                            id="location" required>
                        <option value="Skokie" <?php if ($_SESSION['loca'] == 'Skokie') echo 'selected'; ?>>Skokie</option>
                        <option value="Lombard" <?php if ($_SESSION['loca'] == 'Lombard') echo 'selected'; ?>>Lombard</option>
                        <option value="Devon" <?php if ($_SESSION['loca'] == 'Devon') echo 'selected'; ?>>Devon</option>
                        <option value="Melrose Park" <?php if ($_SESSION['loca'] == 'Melrose Park') echo 'selected'; ?>>Melrose Park</option>
                        <option value="Hanover Park" <?php if ($_SESSION['loca'] == 'Hanover Park') echo 'selected'; ?>>Hanover Park</option>
                        <option value="Bolingbrook" <?php if ($_SESSION['loca'] == 'Bolingbrook') echo 'selected'; ?>>Bolingbrook</option>
                        <option value="Justice" <?php if ($_SESSION['loca'] == 'Justice') echo 'selected'; ?>>Justice</option>
                        <option value="Elgin" <?php if ($_SESSION['loca'] == 'Elgin') echo 'selected'; ?>>Elgin</option>
                        <option value="Albany Park" <?php if ($_SESSION['loca'] == 'Albany Park') echo 'selected'; ?>>Albany Park</option>
                        <option value="Mount Prospect" <?php if ($_SESSION['loca'] == 'Mount Prospect') echo 'selected'; ?>>Mount Prospect</option>
                        <option value="Crystal Lake" <?php if ($_SESSION['loca'] == 'Crystal Lake') echo 'selected'; ?>>Crystal Lake</option>
                        <option value="Rockford" <?php if ($_SESSION['loca'] == 'Rockford') echo 'selected'; ?>>Rockford</option>
                        <option value="Warehouse" <?php if ($_SESSION['loca'] == 'Warehouse') echo 'selected'; ?>>Warehouse</option>
                    </select>
                </div>
            </div>

            <!-- Category Filters -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-700 mb-4 pb-2 border-b">Filter Products</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-2">
                    <button type="button" class="category-btn flex flex-col items-center justify-center p-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition" id="allBtn">
                        <img src="img/warehouse.png" alt="All Products" class="h-12 mb-2">
                        <span class="text-sm font-medium">All Products</span>
                    </button>
                    <button type="button" class="category-btn flex flex-col items-center justify-center p-2 bg-gray-200 text-gray-700 rounded-lg shadow hover:bg-gray-300 transition" id="cleaningBtn">
                        <img src="img/cleaning.png" alt="Cleaning Supplies" class="h-12 mb-2">
                        <span class="text-sm font-medium">Cleaning</span>
                    </button>
                    <button type="button" class="category-btn flex flex-col items-center justify-center p-2 bg-gray-200 text-gray-700 rounded-lg shadow hover:bg-gray-300 transition" id="kitchenBtn">
                        <img src="img/kitchen.png" alt="Kitchen Supplies" class="h-12 mb-2">
                        <span class="text-sm font-medium">Kitchen</span>
                    </button>
                    <button type="button" class="category-btn flex flex-col items-center justify-center p-2 bg-gray-200 text-gray-700 rounded-lg shadow hover:bg-gray-300 transition" id="snacksBtn">
                        <img src="img/snacks.png" alt="Snacks" class="h-12 mb-2">
                        <span class="text-sm font-medium">Snacks</span>
                    </button>
                    <button type="button" class="category-btn flex flex-col items-center justify-center p-2 bg-gray-200 text-gray-700 rounded-lg shadow hover:bg-gray-300 transition" id="beveragesBtn">
                        <img src="img/beverages.png" alt="Beverages" class="h-12 mb-2">
                        <span class="text-sm font-medium">Beverages</span>
                    </button>
                    <button type="button" class="category-btn flex flex-col items-center justify-center p-2 bg-gray-200 text-gray-700 rounded-lg shadow hover:bg-gray-300 transition" id="officeBtn">
                        <img src="img/officeSupply.png" alt="Office Supplies" class="h-12 mb-2">
                        <span class="text-sm font-medium">Office</span>
                    </button>
                    <button type="button" class="category-btn flex flex-col items-center justify-center p-2 bg-gray-200 text-gray-700 rounded-lg shadow hover:bg-gray-300 transition" id="marketingBtn">
                        <img src="img/marketing.png" alt="Marketing" class="h-12 mb-2">
                        <span class="text-sm font-medium">Marketing</span>
                    </button>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="relative mb-6">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" id="searchInput" 
                       class="block w-full pl-10 py-3 px-4 rounded-full border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200"
                       placeholder="Search products...">
            </div>

            <!-- Products Grid -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-700 mb-4 pb-2 border-b">Available Products</h2>
                <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <!-- Product cards will be dynamically inserted here -->
                </div>
            </div>

            <!-- Cart Summary -->
            <div id="cartSummary" class="mb-8 hidden">
                <h2 class="text-xl font-semibold text-gray-700 mb-4 pb-2 border-b">Your Cart</h2>
                <div id="selectedItemsPreview" class="p-4 rounded-lg bg-gray-50">
                    <!-- Selected items summary will appear here -->
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3 px-6 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow transition">
                <i class="fas fa-paper-plane mr-2"></i> Submit Order
            </button>
        </form>

        <!-- Order Summary Modal -->
        <div id="orderSummaryModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
            <div class="absolute inset-0 bg-black bg-opacity-50"></div>
            <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto mx-4">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-800">Order Summary</h3>
                    <button type="button" id="closeModal" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6" id="orderSummary">
                    <!-- Summary details will be populated here -->
                </div>
                <div class="px-6 py-4 border-t flex justify-end space-x-4">
                    <button type="button" id="modifyOrderBtn" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded transition">
                        Modify Order
                    </button>
                    <button id="completeBtn" type="button" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded transition">
                        Complete Order
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Object to store selected products with details persistently
        let selectedProducts = {};
        
        // Toggle cart summary visibility
        function updateCartSummary() {
            const itemCount = Object.keys(selectedProducts).length;
            if (itemCount > 0) {
                // Show the cart and update its content
                $('#cartSummary').removeClass('hidden');
                
                // Create a preview of selected items
                let preview = '<div class="space-y-2">';
                let totalItems = 0;
                
                Object.values(selectedProducts).forEach(item => {
                    totalItems += item.quantity;
                    preview += `
                        <div class="flex justify-between items-center">
                            <div class="font-medium">${item.name}</div>
                            <div class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">${item.quantity}</div>
                        </div>
                    `;
                });
                
                preview += '</div>';
                preview += `<div class="mt-4 pt-2 border-t border-gray-200 flex justify-between font-semibold">
                    <span>Total Items:</span>
                    <span>${totalItems}</span>
                </div>`;
                
                $('#selectedItemsPreview').html(preview);
            } else {
                $('#cartSummary').addClass('hidden');
            }
        }

        // Load products via API
        $.get('/Api/getProds.php', function(data) {
            const products = JSON.parse(data);
            let filteredProducts = products; // Holds filtered products initially showing all

            function renderProducts() {
                $('#productGrid').empty(); // Clear the grid
                
                if (filteredProducts.length === 0) {
                    $('#productGrid').html('<div class="col-span-full text-center py-8 text-gray-500">No products match your search criteria.</div>');
                    return;
                }
                
                filteredProducts.forEach(product => {
                    const productCard = `
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                            <div class="h-48 overflow-hidden">
                                <img src="${product.image_url}" class="w-full h-full object-cover" alt="${product.name}">
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-1">${product.name}</h3>
                                <div class="text-sm text-gray-600 mb-3">
                                    Item # <a href="${product.product_link}" target="_blank" class="text-blue-600 hover:underline">${product.item_number}</a>
                                </div>
                                <div class="flex items-center">
                                    <button class="btn-minus bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-l-md px-3 py-1">-</button>
                                    <input type="number" id="qty-${product.id}" 
                                           class="quantity w-full text-center py-1 border-t border-b border-gray-300" 
                                           value="${selectedProducts[product.id] ? selectedProducts[product.id].quantity : 0}" 
                                           min="0" data-product-id="${product.id}">
                                    <button class="btn-plus bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-r-md px-3 py-1">+</button>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#productGrid').append(productCard);
                });
                
                // Initialize quantity change triggers
                $('.quantity').on('change', function() {
                    updateProductQuantity($(this));
                });
            }

            // Render all products initially
            renderProducts();
            
            // Handle category filtering
            $('.category-btn').on('click', function() {
                // Change button styles
                $('.category-btn').removeClass('bg-green-600 text-white').addClass('bg-gray-200 text-gray-700');
                $(this).removeClass('bg-gray-200 text-gray-700').addClass('bg-green-600 text-white');

                const filter = $(this).attr('id');

                if (filter === 'allBtn') {
                    filteredProducts = products; // Show all products
                } else if (filter === 'cleaningBtn') {
                    filteredProducts = products.filter(p => p.section === 'Cleaning Supplies');
                } else if (filter === 'kitchenBtn') {
                    filteredProducts = products.filter(p => p.section === 'Kitchen Supplies');
                } else if (filter === 'snacksBtn') {
                    filteredProducts = products.filter(p => p.section === 'Snacks');
                } else if (filter === 'officeBtn') {
                    filteredProducts = products.filter(p => p.section === 'Office Supply');
                } else if (filter === 'beveragesBtn') {
                    filteredProducts = products.filter(p => p.section === 'Beverages');
                } else if (filter === 'marketingBtn') {
                    filteredProducts = products.filter(p => p.section === 'Marketing');
                }

                renderProducts();
            });

            // Real-time search functionality
            $('#searchInput').on('input', function() {
                const searchQuery = $(this).val().toLowerCase();
                filteredProducts = products.filter(p => p.name.toLowerCase().includes(searchQuery));
                renderProducts();
            });

            // Attach click event handlers for the + and - buttons after rendering
            $(document).on('click', '.btn-minus', function(e) {
                e.preventDefault(); // Prevent any form submission
                e.stopPropagation(); // Prevent event bubbling
                let input = $(this).siblings('input.quantity');
                let currentValue = parseInt(input.val());
                if (currentValue > 0) {
                    input.val(currentValue - 1);
                    updateProductQuantity(input);
                }
            });

            $(document).on('click', '.btn-plus', function(e) {
                e.preventDefault(); // Prevent any form submission
                e.stopPropagation(); // Prevent event bubbling
                let input = $(this).siblings('input.quantity');
                let currentValue = parseInt(input.val());
                input.val(currentValue + 1);
                updateProductQuantity(input);
            });

            // Update selected product details when the quantity changes
            function updateProductQuantity(input) {
                const productId = input.data('product-id');
                const productCard = input.closest('.bg-white');
                const productName = productCard.find('h3').text();
                const productLink = productCard.find('a').attr('href');
                const itemNumber = productCard.find('a').text();
                const quantity = parseInt(input.val());

                if (quantity > 0) {
                    // Update or add the product in the selectedProducts object
                    selectedProducts[productId] = {
                        id: productId,
                        name: productName,
                        quantity: quantity,
                        itemNumber: itemNumber,
                        link: productLink
                    };
                } else {
                    // Remove the product if quantity is 0
                    delete selectedProducts[productId];
                }
                
                // Update the cart summary
                updateCartSummary();
            }
        });

        // Handle modal visibility
        function showModal() {
            $('#orderSummaryModal').removeClass('hidden');
        }
        
        function hideModal() {
            $('#orderSummaryModal').addClass('hidden');
        }
        
        $('#closeModal, #modifyOrderBtn').on('click', hideModal);
        
        // Close modal when clicking outside the content
        $('#orderSummaryModal').on('click', function(e) {
            if ($(e.target).closest('.relative').length === 0) {
                hideModal();
            }
        });

        // Modal only appears after form submission
        $('#orderForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            let order = Object.values(selectedProducts); // Get all selected products as an array

            if (order.length > 0) {
                // Only show the modal when products are selected
                let summary = `
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                                    <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Number</th>
                                    <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Product Link</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">`;

                order.forEach((item, index) => {
                    const rowClass = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
                    summary += `
                        <tr class="${rowClass}">
                            <td class="px-4 py-3 text-sm text-gray-900">${item.name}</td>
                            <td class="px-4 py-3 text-sm text-center text-gray-900">${item.quantity}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">${item.itemNumber}</td>
                            <td class="px-4 py-3 text-sm text-center">
                                <a href="${item.link}" target="_blank" class="text-blue-600 hover:underline">View Product</a>
                            </td>
                        </tr>`;
                });

                summary += `
                            </tbody>
                        </table>
                    </div>`;

                $('#orderSummary').html(summary);
                showModal(); // Show modal after form is submitted
            } else {
                alert('Please select at least one product to order.');
            }
        });

        // Submit final order when 'Complete Order' is clicked
        $('#completeBtn').on('click', function() {
            const name = $('#name').val();
            const email = $('#email').val();
            const location = $('#location').val();

            // Get the final order details
            const order = Object.values(selectedProducts);

            // Store the order in sessionStorage
            sessionStorage.setItem('order', JSON.stringify(order));
            sessionStorage.setItem('name', name);
            sessionStorage.setItem('email', email);
            sessionStorage.setItem('location', location);

            // Post the data and redirect to the thank-you page
            $.post('/Api/submitOrder.php', { 
                name, 
                email, 
                location, 
                order: JSON.stringify(order) 
            }, function(response) {
                // Redirect to thank you page
                window.location.href = '/thank-you.html';
            });
        });
    });
    </script>
</body>
</html>