<?php
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

// Define the allowed users
$allowed_users = ['youssefa@saharahomecare.com', 'armaghan@saharahomecare.com', 'portalinquiries@saharahomecare.com'];

// Check if the user is authorized
if (!in_array($_SESSION['email'], $allowed_users)) {
    // Display an error message if the user is not authorized
    echo '<div style="text-align: center; margin-top: 50px;">
            <h1>Access Denied</h1>
            <p>You do not have permission to access this page.</p>
            <a href="index.php" class="btn btn-primary">Go Back to Home</a>
          </div>';
    exit(); // Stop further execution of the page
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container my-4">
    <h2>Manage Products</h2>

    <!-- Filter buttons -->
    <div class="mb-3">
        <button class="btn btn-primary" onclick="filterProducts('All')">All</button>
        <button class="btn btn-secondary" onclick="filterProducts('Cleaning Supplies')">Cleaning Supplies</button>
        <button class="btn btn-secondary" onclick="filterProducts('Snacks')">Snacks</button>
        <button class="btn btn-secondary" onclick="filterProducts('Beverages')">Beverages</button>
    </div>

    <!-- Search bar -->
    <div class="mb-3">
        <input type="text" id="searchBox" class="form-control" placeholder="Search product..." oninput="filterAndSearchProducts()">
    </div>

    <!-- Product table -->
    <table class="table table-bordered" id="productTable">
        <thead>
            <tr>
                <th>Image</th>
                <th>Product Name</th>
                <th>Section</th>
                <th>Item Number</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="productTableBody">
            <!-- Products will be dynamically injected here -->
        </tbody>
    </table>

    <!-- Pagination -->
    <nav>
        <ul class="pagination" id="pagination">
            <!-- Pagination buttons will be dynamically injected here -->
        </ul>
    </nav>

    <!-- Add new product button -->
    <button class="btn btn-success" onclick="showAddProductModal()">Add New Product</button>
</div>

<!-- Modal for editing or adding a product -->
<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Edit Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    <input type="hidden" id="productId" name="productId">

                    <div class="form-group">
                        <label for="productName">Product Name</label>
                        <input type="text" class="form-control" id="productName" required>
                    </div>

                    <div class="form-group">
                        <label for="productSection">Product Section</label>
                        <select class="form-control" id="productSection" required>
                            <option value="Cleaning Supplies">Cleaning Supplies</option>
                            <option value="Kitchen Supplies">Kitchen Supplies</option>
                            <option value="Beverages">Beverages</option>
                            <option value="Snacks">Snacks</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="itemNumber">Item Number</label>
                        <input type="text" class="form-control" id="itemNumber" required>
                    </div>

                    <div class="form-group">
                        <label for="productLink">Product Link</label>
                        <input type="url" class="form-control" id="productLink" required>
                    </div>

                    <div class="form-group">
                        <label for="productImage">Product Image</label>
                        <input type="file" class="form-control" id="productImage">
                    </div>

                    <button type="submit" class="btn btn-primary" id="saveProductBtn">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
// Global variables to store product data
let allProducts = [];
let filteredProducts = [];
let currentSection = 'All';
let searchQuery = '';
let itemsPerPage = 10;
let currentPage = 1;

// Fetch products from the API when the page is ready
$(document).ready(function() {
    $.get('/Api/getProds.php', function(data) {
        allProducts = JSON.parse(data);
        filteredProducts = allProducts;
        displayProducts();
    });

    // Save or update a product
    $('#saveProductBtn').on('click', function() {
        const formData = new FormData($('#productForm')[0]);
        const productId = $('#productId').val(); // This should contain the ID for an update
    
        let apiUrl = '/Api/addProduct.php';  // Default to add product
        if (productId) {
            apiUrl = '/Api/updateProduct.php';  // Switch to update product if productId is present
        }
    
        $.ajax({
            url: apiUrl,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert(response.message);
                if (response.status === 'success') {
                    $('#productModal').modal('hide');
                    location.reload(); // Reload to reflect changes
                }
            }
        });
    });

});

// Display products in the table with pagination
function displayProducts() {
    const productTableBody = document.getElementById('productTableBody');
    productTableBody.innerHTML = '';

    // Calculate the start and end index for the current page
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const productsToDisplay = filteredProducts.slice(startIndex, endIndex);

    productsToDisplay.forEach(product => {
        const productRow = `
            <tr>
                <td><img src="${product.image_url}" alt="${product.name}" style="width: 50px;"></td>
                <td>${product.name}</td>
                <td>${product.section}</td>
                <td>${product.item_number}</td>
                <td>
                    <button class="btn btn-info" onclick="showEditProductModal(${product.id})">Edit</button>
                </td>
            </tr>
        `;
        productTableBody.innerHTML += productRow;
    });

    updatePagination();
}

// Filter products by section
function filterProducts(section) {
    currentSection = section;
    filterAndSearchProducts();
}

// Real-time search and filter combined
function filterAndSearchProducts() {
    searchQuery = $('#searchBox').val();  // Update the search query
    filteredProducts = allProducts.filter(product => {
        const matchesSection = currentSection === 'All' || product.section === currentSection;
        const matchesSearch = product.name.toLowerCase().includes(searchQuery.toLowerCase());
        return matchesSection && matchesSearch;
    });

    currentPage = 1; // Reset to the first page
    displayProducts();
}

// Update pagination buttons
function updatePagination() {
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';

    const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
    for (let i = 1; i <= totalPages; i++) {
        const activeClass = i === currentPage ? 'active' : '';
        pagination.innerHTML += `<li class="page-item ${activeClass}"><a class="page-link" href="#" onclick="goToPage(${i})">${i}</a></li>`;
    }
}

// Go to a specific page
function goToPage(page) {
    currentPage = page;
    displayProducts();
}

// Show the modal for editing a product
function showEditProductModal(productId) {
    const product = allProducts.find(p => p.id == productId);

    // Populate the modal with product data
    $('#productId').val(product.id); // Make sure this field is properly set
    $('#productName').val(product.name);
    $('#productSection').val(product.section);
    $('#itemNumber').val(product.item_number);
    $('#productLink').val(product.product_link);

    // Ensure the modal is displayed
    $('#productModal').modal('show');
}


// Show the modal for adding a new product
function showAddProductModal() {
    // Clear the form and reset the modal title
    $('#productForm')[0].reset(); 
    $('#productModalLabel').text('Add New Product');
    $('#productId').val(''); // Make sure no product ID is set (new product)

    // Ensure the modal is displayed
    $('#productModal').modal('show');
}
</script>

</body>
</html>

