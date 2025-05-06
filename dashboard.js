// dashboard.js - Enhanced dashboard functionality

document.addEventListener('DOMContentLoaded', function() {
    // Set current date
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', options);
    
    // Fetch dashboard data from our unified API
    fetchDashboardData();
    
    // Set up event listeners
    setupEventListeners();
});

function setupEventListeners() {
    // Navigation links
    document.getElementById('viewAllOrders').addEventListener('click', function() {
        window.location.href = 'orders.php';
    });
    
    document.getElementById('viewAllProducts').addEventListener('click', function() {
        window.location.href = 'manage-products.php';
    });
    
    // For admin-only buttons
    const generateReportBtn = document.getElementById('generateReport');
    if (generateReportBtn) {
        generateReportBtn.addEventListener('click', generateReport);
    }
}

function fetchDashboardData() {
    // Show loading indicators
    document.getElementById('totalOrders').textContent = '...';
    document.getElementById('pendingOrders').textContent = '...';
    document.getElementById('totalProducts').textContent = '...';
    
    // Fetch all dashboard data at once from our unified API
    fetch('/Api/getUserDashboardData.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Update dashboard with retrieved data
            updateDashboard(data);
        })
        .catch(error => {
            console.error('Error fetching dashboard data:', error);
            showErrorMessage('Failed to load dashboard data. Please refresh the page.');
        });
}

function updateDashboard(data) {
    // Update dashboard stats
    document.getElementById('totalOrders').textContent = data.total_orders;
    document.getElementById('pendingOrders').textContent = data.pending_orders;
    document.getElementById('totalProducts').textContent = data.total_products;
    
    // Update change indicators (this would usually be calculated based on previous period)
    // For now, we'll just show placeholder values
    document.getElementById('ordersChange').textContent = '+5%';
    document.getElementById('pendingChange').textContent = '-2%';
    document.getElementById('productsChange').textContent = '+3%';
    
    // Populate recent orders table
    populateRecentOrders(data.recent_orders);
    
    // Populate top products list
    populateTopProducts(data.top_products);
    
    // Handle admin-specific content
    if (data.is_admin) {
        // Show manager section
        showManagerSection();
        
        // Create charts with data
        if (data.location_stats) {
            createOrdersByLocationChart(data.location_stats);
        }
        
        if (data.status_stats) {
            createOrderStatusChart(data.status_stats);
        }
        
        if (data.monthly_stats) {
            createMonthlyOrdersChart(data.monthly_stats);
        }
    }
}

function populateRecentOrders(orders) {
    const tableBody = document.getElementById('recentOrdersTable');
    tableBody.innerHTML = '';
    
    if (!orders || orders.length === 0) {
        const row = document.createElement('tr');
        row.className = 'text-gray-700';
        row.innerHTML = `
            <td class="px-4 py-3 text-center" colspan="5">
                <div class="flex flex-col items-center justify-center py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-gray-500">No orders found</p>
                </div>
            </td>
        `;
        tableBody.appendChild(row);
        return;
    }
    
    orders.forEach(order => {
        const row = document.createElement('tr');
        row.className = 'text-gray-700 hover:bg-gray-50 transition-colors';
        
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
    
    if (!products || products.length === 0) {
        productsList.innerHTML = `
            <div class="flex flex-col items-center justify-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <p class="text-gray-500">No products data available</p>
            </div>
        `;
        return;
    }
    
    products.forEach((product, index) => {
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
        if (index < products.length - 1) {
            const divider = document.createElement('div');
            divider.className = 'border-t border-gray-200 my-2';
            productsList.appendChild(divider);
        }
    });
}

function showManagerSection() {
    const managerSection = document.getElementById('managerSection');
    if (managerSection) {
        managerSection.classList.remove('hidden');
    }
}

function createOrdersByLocationChart(locationStats) {
    // Clear chart container
    const container = document.getElementById('ordersByLocationChart');
    container.innerHTML = '';
    
    // Create canvas for the chart
    const canvas = document.createElement('canvas');
    container.appendChild(canvas);
    
    // Prepare data for chart
    const locations = locationStats.map(stat => stat.location);
    const counts = locationStats.map(stat => stat.count);
    
    // Create chart
    const ctx = canvas.getContext('2d');
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

function createOrderStatusChart(statusStats) {
    // Clear chart container
    const container = document.getElementById('orderStatusChart');
    container.innerHTML = '';
    
    // Create canvas for the chart
    const canvas = document.createElement('canvas');
    container.appendChild(canvas);
    
    // Prepare data for chart
    const statuses = statusStats.map(stat => stat.Status);
    const counts = statusStats.map(stat => stat.count);
    
    // Define colors for each status
    const backgroundColors = {
        'Pending': 'rgba(245, 158, 11, 0.6)',
        'Completed': 'rgba(34, 197, 94, 0.6)',
        'Cancelled': 'rgba(239, 68, 68, 0.6)',
        'Processing': 'rgba(59, 130, 246, 0.6)',
        'Delivered': 'rgba(16, 185, 129, 0.6)'
    };
    
    const borderColors = {
        'Pending': 'rgba(245, 158, 11, 1)',
        'Completed': 'rgba(34, 197, 94, 1)',
        'Cancelled': 'rgba(239, 68, 68, 1)',
        'Processing': 'rgba(59, 130, 246, 1)',
        'Delivered': 'rgba(16, 185, 129, 1)'
    };
    
    // For any status without a defined color, use a default
    const getBackgroundColor = status => {
        return backgroundColors[status] || 'rgba(107, 114, 128, 0.6)';
    };
    
    const getBorderColor = status => {
        return borderColors[status] || 'rgba(107, 114, 128, 1)';
    };
    
    // Create chart
    const ctx = canvas.getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: statuses,
            datasets: [{
                data: counts,
                backgroundColor: statuses.map(getBackgroundColor),
                borderColor: statuses.map(getBorderColor),
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

function createMonthlyOrdersChart(monthlyStats) {
    // Clear chart container
    const container = document.getElementById('monthlyOrdersChart');
    container.innerHTML = '';
    
    // Create canvas for the chart
    const canvas = document.createElement('canvas');
    container.appendChild(canvas);
    
    // Create array for all months (1-12)
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const counts = Array(12).fill(0);
    
    // Fill in the data we have
    monthlyStats.forEach(stat => {
        const monthIndex = parseInt(stat.month) - 1; // Month from SQL is 1-12, array is 0-11
        if (monthIndex >= 0 && monthIndex < 12) {
            counts[monthIndex] = parseInt(stat.count);
        }
    });
    
    // Create chart
    const ctx = canvas.getContext('2d');
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
    // This function would generate a PDF or Excel report based on the dashboard data
    
    // For demonstration purposes, show a notification
    const notification = document.createElement('div');
    notification.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg';
    notification.innerHTML = `
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Report generation initiated. File will be available shortly.</span>
        </div>
    `;
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.classList.add('opacity-0', 'transition-opacity', 'duration-500');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 500);
    }, 3000);
}

function showErrorMessage(message) {
    // Create an error message element
    const errorDiv = document.createElement('div');
    errorDiv.className = 'bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6';
    errorDiv.innerHTML = `
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <p>${message}</p>
        </div>
    `;
    
    // Insert at the top of the dashboard
    const dashboard = document.querySelector('.relative.mx-auto.lg\\:max-w-7xl');
    dashboard.insertBefore(errorDiv, dashboard.firstChild);
}