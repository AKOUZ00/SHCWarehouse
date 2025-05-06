<!-- dashboard.php -->
<div class="relative mx-auto lg:max-w-7xl aud">
  <div class="pt-2">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-2xl/7 font-bold text-gray-900 sm:text-3xl sm:tracking-tight">Dashboard</h2>
      <div class="text-sm text-gray-500">
        <span id="currentDate"></span>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid gap-6 mb-8 md:grid-cols-3">
      <!-- Total Orders Card -->
      <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-800">Total Orders</h3>
          <div class="p-2 rounded-full bg-green-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
        </div>
        <div class="flex items-end justify-between">
          <p id="totalOrders" class="text-3xl font-bold text-gray-900">0</p>
          <div class="flex items-center text-sm text-green-600">
            <span id="ordersChange" class="font-medium">0%</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
          </div>
        </div>
      </div>
      
      <!-- Pending Orders Card -->
      <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-800">Pending Orders</h3>
          <div class="p-2 rounded-full bg-amber-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
        <div class="flex items-end justify-between">
          <p id="pendingOrders" class="text-3xl font-bold text-gray-900">0</p>
          <div class="flex items-center text-sm text-amber-600">
            <span id="pendingChange" class="font-medium">0%</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
          </div>
        </div>
      </div>
      
      <!-- Available Products Card -->
      <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-800">Available Products</h3>
          <div class="p-2 rounded-full bg-blue-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
        </div>
        <div class="flex items-end justify-between">
          <p id="totalProducts" class="text-3xl font-bold text-gray-900">0</p>
          <div class="flex items-center text-sm text-blue-600">
            <span id="productsChange" class="font-medium">0%</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Activity & Analytics Section -->
    <div class="grid gap-6 mb-8 md:grid-cols-2 lg:grid-cols-3">
      <!-- Recent Activity -->
      <div class="lg:col-span-2 p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-800">Recent Orders</h3>
          <button id="viewAllOrders" class="text-sm font-medium text-green-600 hover:text-green-700">View All</button>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                <th class="px-4 py-3">Order ID</th>
                <th class="px-4 py-3">User</th>
                <th class="px-4 py-3">Location</th>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3">Status</th>
              </tr>
            </thead>
            <tbody id="recentOrdersTable" class="bg-white divide-y">
              <!-- Dynamic content will be inserted here -->
              <tr class="text-gray-700">
                <td class="px-4 py-3" colspan="5">Loading recent orders...</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
      <!-- Top Products -->
      <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-800">Top Products</h3>
          <button id="viewAllProducts" class="text-sm font-medium text-green-600 hover:text-green-700">View All</button>
        </div>
        <div id="topProductsList" class="space-y-4">
          <!-- Dynamic content will be inserted here -->
          <div class="flex items-center">
            <div class="flex-shrink-0 mr-3">
              <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
              </div>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-900">Loading products...</p>
              <p class="text-xs text-gray-500">Please wait</p>
            </div>
            <div class="ml-auto text-xs text-gray-500">
              0
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Order Management Section (Visible to managers only) -->
    <div id="managerSection" class="hidden mb-8">
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Management</h3>
        
        <div class="grid gap-6 mb-6 md:grid-cols-3">
          <!-- Orders Per Location Chart -->
          <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="text-sm font-medium text-gray-700 mb-2">Orders by Location</h4>
            <div id="ordersByLocationChart" class="h-64">
              <!-- Chart will be inserted here -->
              <div class="flex items-center justify-center h-full">
                <p class="text-gray-500 text-sm">Loading chart...</p>
              </div>
            </div>
          </div>
          
          <!-- Order Status Chart -->
          <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="text-sm font-medium text-gray-700 mb-2">Order Status</h4>
            <div id="orderStatusChart" class="h-64">
              <!-- Chart will be inserted here -->
              <div class="flex items-center justify-center h-full">
                <p class="text-gray-500 text-sm">Loading chart...</p>
              </div>
            </div>
          </div>
          
          <!-- Monthly Orders Chart -->
          <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="text-sm font-medium text-gray-700 mb-2">Monthly Orders</h4>
            <div id="monthlyOrdersChart" class="h-64">
              <!-- Chart will be inserted here -->
              <div class="flex items-center justify-center h-full">
                <p class="text-gray-500 text-sm">Loading chart...</p>
              </div>
            </div>
          </div>
        </div>
        
        <div class="flex justify-end">
          <button id="generateReport" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
            Generate Report
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript for the Dashboard -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="dashboard.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set current date
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', options);
    
    // Check if user is manager
    const isManager = checkIfManager();
    if (isManager) {
        document.getElementById('managerSection').classList.remove('hidden');
    }
    
    // Fetch dashboard data
    fetchDashboardData();
    
    // Event listeners
    document.getElementById('viewAllOrders').addEventListener('click', function() {
        window.location.href = 'orders.php';
    });
    
    document.getElementById('viewAllProducts').addEventListener('click', function() {
        window.location.href = 'manage-products.php';
    });
    
    if (isManager) {
        document.getElementById('generateReport').addEventListener('click', generateReport);
    }
});

function checkIfManager() {
    // This would typically be determined by the server-side session
    // For now, we'll check if the user email is one of the manager emails
    const managerEmails = ['youssefa@saharahomecare.com', 'armaghan@saharahomecare.com', 'fahadk@saharahomecare.com'];
    // Assume there's a global userEmail variable set by the server
    // You would need to modify this based on how your authentication works
    const userEmail = '<?php echo $_SESSION["email"]; ?>';
    return managerEmails.includes(userEmail);
}

function fetchDashboardData() {
    // Fetch total orders
    fetch('/Api/getTotalOrders.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('totalOrders').textContent = data.total_orders || '0';
        })
        .catch(error => console.error('Error fetching total orders:', error));
    
    // Fetch pending orders
    fetch('/Api/getPendingOrders.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('pendingOrders').textContent = data.total_pending_orders || '0';
        })
        .catch(error => console.error('Error fetching pending orders:', error));
    
    // Fetch total products
    fetch('/Api/getAvailableProducts.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('totalProducts').textContent = data.total_products || '0';
        })
        .catch(error => console.error('Error fetching total products:', error));
    
    // Fetch recent orders
    fetch('/Api/getOrders.php')
        .then(response => response.json())
        .then(data => {
            populateRecentOrders(data);
        })
        .catch(error => console.error('Error fetching recent orders:', error));
    
    // Fetch top products
    fetch('/Api/getTopProducts.php')
        .then(response => response.json())
        .then(data => {
            populateTopProducts(data);
        })
        .catch(error => console.error('Error fetching top products:', error));
    
    // If user is manager, fetch additional data for charts
    if (checkIfManager()) {
        createManagerCharts();
    }
}

function populateRecentOrders(orders) {
    const tableBody = document.getElementById('recentOrdersTable');
    tableBody.innerHTML = '';
    
    // Show only the 5 most recent orders
    const recentOrders = orders.slice(0, 5);
    
    if (recentOrders.length === 0) {
        const row = document.createElement('tr');
        row.className = 'text-gray-700';
        row.innerHTML = '<td class="px-4 py-3" colspan="5">No orders found</td>';
        tableBody.appendChild(row);
        return;
    }
    
    recentOrders.forEach(order => {
        const row = document.createElement('tr');
        row.className = 'text-gray-700';
        
        // Format date
        const orderDate = new Date(order.order_date);
        const formattedDate = orderDate.toLocaleDateString('en-US', {
            year: 'numeric', 
            month: 'short', 
            day: 'numeric'
        });
        
        // Get status badge class
        let statusClass = 'bg-gray-100 text-gray-800';
        if (order.Status === 'Completed') {
            statusClass = 'bg-green-100 text-green-800';
        } else if (order.Status === 'Pending') {
            statusClass = 'bg-yellow-100 text-yellow-800';
        } else if (order.Status === 'Cancelled') {
            statusClass = 'bg-red-100 text-red-800';
        }
        
        row.innerHTML = `
            <td class="px-4 py-3">#${order.order_id}</td>
            <td class="px-4 py-3">${order.name}</td>
            <td class="px-4 py-3">${order.location}</td>
            <td class="px-4 py-3">${formattedDate}</td>
            <td class="px-4 py-3">
                <span class="px-2 py-1 font-semibold leading-tight rounded-full ${statusClass}">
                    ${order.Status}
                </span>
            </td>
        `;
        
        tableBody.appendChild(row);
    });
}

function populateTopProducts(products) {
    const productsList = document.getElementById('topProductsList');
    productsList.innerHTML = '';
    
    if (!Array.isArray(products) || products.length === 0) {
        productsList.innerHTML = '<p class="text-sm text-gray-500">No products data available</p>';
        return;
    }
    
    // Show only top 5 products
    const topProducts = products.slice(0, 5);
    
    topProducts.forEach(product => {
        const productItem = document.createElement('div');
        productItem.className = 'flex items-center';
        
        // Create image element
        const imgSrc = product.image_url ? product.image_url : '/img/placeholder.png';
        
        productItem.innerHTML = `
            <div class="flex-shrink-0 mr-3">
                <div class="w-10 h-10 rounded overflow-hidden">
                    <img src="${imgSrc}" alt="${product.name}" class="w-full h-full object-cover" onerror="this.src='/img/placeholder.png'">
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <a href="${product.product_link}" target="_blank" class="text-sm font-medium text-gray-900 hover:text-green-600 truncate">
                    ${product.name}
                </a>
                <p class="text-xs text-gray-500 truncate">${product.item_number || 'No item number'}</p>
            </div>
            <div class="ml-auto text-sm font-medium text-gray-900">
                ${product.total_quantity}
            </div>
        `;
        
        productsList.appendChild(productItem);
        
        // Add divider
        if (topProducts.indexOf(product) < topProducts.length - 1) {
            const divider = document.createElement('div');
            divider.className = 'border-t border-gray-200 my-2';
            productsList.appendChild(divider);
        }
    });
}

function createManagerCharts() {
    // Fetch orders data for charts
    fetch('/Api/getOrders.php')
        .then(response => response.json())
        .then(data => {
            createOrdersByLocationChart(data);
            createOrderStatusChart(data);
            createMonthlyOrdersChart(data);
        })
        .catch(error => console.error('Error fetching orders for charts:', error));
}

function createOrdersByLocationChart(orders) {
    // Group orders by location
    const locationCounts = {};
    orders.forEach(order => {
        locationCounts[order.location] = (locationCounts[order.location] || 0) + 1;
    });
    
    // Prepare data for chart
    const locations = Object.keys(locationCounts);
    const counts = Object.values(locationCounts);
    
    // Create chart
    const ctx = document.getElementById('ordersByLocationChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: locations,
            datasets: [{
                label: 'Number of Orders',
                data: counts,
                backgroundColor: 'rgba(34, 197, 94, 0.6)',
                borderColor: 'rgba(34, 197, 94, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
}

function createOrderStatusChart(orders) {
    // Group orders by status
    const statusCounts = {
        'Pending': 0,
        'Completed': 0,
        'Cancelled': 0,
        'Other': 0
    };
    
    orders.forEach(order => {
        if (statusCounts.hasOwnProperty(order.Status)) {
            statusCounts[order.Status]++;
        } else {
            statusCounts['Other']++;
        }
    });
    
    // Remove statuses with zero count
    const filteredStatuses = {};
    Object.keys(statusCounts).forEach(status => {
        if (statusCounts[status] > 0) {
            filteredStatuses[status] = statusCounts[status];
        }
    });
    
    // Prepare data for chart
    const statuses = Object.keys(filteredStatuses);
    const counts = Object.values(filteredStatuses);
    
    // Define colors for each status
    const backgroundColors = {
        'Pending': 'rgba(245, 158, 11, 0.6)',
        'Completed': 'rgba(34, 197, 94, 0.6)',
        'Cancelled': 'rgba(239, 68, 68, 0.6)',
        'Other': 'rgba(107, 114, 128, 0.6)'
    };
    
    const borderColors = {
        'Pending': 'rgba(245, 158, 11, 1)',
        'Completed': 'rgba(34, 197, 94, 1)',
        'Cancelled': 'rgba(239, 68, 68, 1)',
        'Other': 'rgba(107, 114, 128, 1)'
    };
    
    // Create chart
    const ctx = document.getElementById('orderStatusChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: statuses,
            datasets: [{
                data: counts,
                backgroundColor: statuses.map(status => backgroundColors[status]),
                borderColor: statuses.map(status => borderColors[status]),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function createMonthlyOrdersChart(orders) {
    // Group orders by month
    const monthlyOrders = {};
    const currentYear = new Date().getFullYear();
    
    orders.forEach(order => {
        const orderDate = new Date(order.order_date);
        if (orderDate.getFullYear() === currentYear) {
            const month = orderDate.getMonth();
            monthlyOrders[month] = (monthlyOrders[month] || 0) + 1;
        }
    });
    
    // Create array for all months (0-11)
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const counts = months.map((_, index) => monthlyOrders[index] || 0);
    
    // Create chart
    const ctx = document.getElementById('monthlyOrdersChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Orders',
                data: counts,
                fill: false,
                borderColor: 'rgba(59, 130, 246, 1)',
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                tension: 0.3,
                pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
}

function generateReport() {
    // This function would generate a report based on the dashboard data
    // For now, we'll just show an alert
    alert('Report generation is not implemented yet.');
}
</script>