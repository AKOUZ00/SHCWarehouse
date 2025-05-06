// app.js
$(document).ready(function() {

    // Load Orders table by default
    loadDashboard();

    // Navigate between Orders and New Order
    $('#dashboardLink').on('click', function() {
        loadDashboard();
    });

    // Navigate between Orders and New Order
    $('#ordersLink').on('click', function() {
        loadOrders();
    });

    $('#newOrderLink').on('click', function() {
        loadNewOrderForm();
    });
    
    $('#ProductsList').on('click', function() {
        loadProductsList();
    });

    function loadDashboard() {
        $.get('dashboard.php', function(data) {
            $('#mainContent').html(data);
        });
    }

    function loadOrders() {
        $.get('orders.php', function(data) {
            $('#mainContent').html(data);
        });
    }

    function loadNewOrderForm() {
        $.get('new-order.php', function(data) {
            $('#mainContent').html(data);
        });
    }
    
    function loadProductsList() {
        $.get('manage-products.php', function(data) {
            $('#mainContent').html(data);
        });
    }
    
});
