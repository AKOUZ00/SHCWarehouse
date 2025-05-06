<?php
session_start();
if (!isset($_SESSION['email'])) {
  header('Location: login.php');
  exit();
}

?>
<div  class="relative z-10" aria-labelledby="slide-over-title" role="dialog" aria-modal="false">
  <!--
    Background backdrop, show/hide based on slide-over state.

    Entering: "ease-in-out duration-500"
      From: "opacity-0"
      To: "opacity-100"
    Leaving: "ease-in-out duration-500"
      From: "opacity-100"
      To: "opacity-0"
  -->
    <div id="drawerp1" class="" aria-hidden="false"></div>

      <div id="drawer" class="translate-x-full pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
        <!--
          Slide-over panel, show/hide based on slide-over state.

          Entering: "transform transition ease-in-out duration-500 sm:duration-700"
            From: "translate-x-full"
            To: "translate-x-0"
          Leaving: "transform transition ease-in-out duration-500 sm:duration-700"
            From: "translate-x-0"
            To: "translate-x-full"
        -->
        <div class="pointer-events-auto relative w-screen max-w-md">
          <!--
            Close button, show/hide based on slide-over state.

            Entering: "ease-in-out duration-500"
              From: "opacity-0"
              To: "opacity-100"
            Leaving: "ease-in-out duration-500"
              From: "opacity-100"
              To: "opacity-0"
          -->
          <div class="absolute left-0 top-0 -ml-8 flex pr-2 pt-4 sm:-ml-10 sm:pr-4">
            <button type="button" class="relative rounded-md text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-white">
              <span id="closeDrawer" class="absolute -inset-2.5"></span>
              <span class="sr-only">Close panel</span>
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <div class="flex h-full flex-col overflow-y-scroll bg-white py-6 shadow-xl">
            <div class="px-4 sm:px-6">
              <h2 class="text-base font-semibold text-gray-900" id="slide-over-title">Order Details</h2>
            </div>
            <div class="relative mt-6 flex-1 px-4 sm:px-6">
              <div id="drawerContent" class="p-6">
                <h3 id="orderTitle" class="text-xl font-semibold">Order Details</h3>
                <!-- Updated to match the JavaScript variable -->
                <div id="orderDetails" class="mt-4">Loading...</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

<div class="relative mx-auto lg:max-w-7xl aud">
  <div class="pt-2">
    <h2 class="text-2xl/7 font-bold text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight"
      style=" font-size: 2rem; padding-bottom: 1rem; ">Orders</h2>
    <div
      class="relative w-full rounded-xl bg-white shadow-[0px_0px_0px_1px_rgba(9,9,11,0.07),0px_2px_2px_0px_rgba(9,9,11,0.05)] forced-colors:outline">
      <ul role="list" class="divide-y divide-gray-100" id="orderTableBody">

        <!-- Orders will be injected here via JavaScript -->

      </ul>
    </div>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
  $(document).ready(function () {

    // Fetch orders from the API
    $.get('/Api/getOrders.php', function (data) {
      let orderTableBody = $('#orderTableBody');

      // Fetching orders
      data.forEach(function (order) {
        let tableRow = `
                <li id="order-${order.order_id}" data-order-id="${order.order_id}" class="order-item flex justify-between gap-x-6" style="padding: 1.25rem;">
                  <div class="flex min-w-0 gap-x-4">
                    <svg class="azb h-12 w-12 flex-none rounded-full bg-gray-50" style="height: 48px;" fill="currentColor" viewBox="0 0 24 24" class="pv uf azb"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <div class="min-w-0 flex-auto">
                      <p class="text-sm/6 font-semibold text-gray-900">${order.name}</p>
                      <p class="mt-1 truncate text-xs/5 text-gray-500"><span style="font-weight:bold;">${order.location}</span> | ${order.email}</p>
                    </div>
                    <div class="flex -space-x-2 overflow-hidden" style="height: 48px;">`;

        // Display up to 7 product images or a placeholder image
        if (order.products.length > 7) {
          for (let i = 0; i < 7; i++) {
            const product = order.products[i];
            tableRow += `
                    <div class="relative inline-block h-10 w-10 rounded-full">
                      <img src="${product.image_url}" alt="${product.product_name}" class="absolute inset-0 object-cover rounded-full" style="filter: brightness(0.67);">
                      <span class="absolute inset-0 flex items-center justify-center text-white font-bold">${product.quantity}</span>
                    </div>`;
          }
          tableRow += `
                  <div class="relative inline-block h-10 w-10 rounded-full">
                    <div class="absolute inset-0 bg-black rounded-full flex items-center justify-center" style="background: #1ba84f;">
                      <span class="text-white font-bold">More</span>
                    </div>
                  </div>`;
        } else {
          for (let i = 0; i < order.products.length; i++) {
            const product = order.products[i];
            tableRow += `
                    <div class="relative inline-block h-10 w-10 rounded-full">
                      <img src="${product.image_url}" alt="${product.product_name}" class="absolute inset-0 object-cover rounded-full" style="filter: brightness(0.67);">
                      <span class="absolute inset-0 flex items-center justify-center text-white font-bold">${product.quantity}</span>
                    </div>`;
          }
        }

        tableRow += `
                                        </div>
                  </div>
                  <div class="ma vj zr aba" bis_size="{'x':938,'y':68,'w':158,'h':48,'abs_x':1241,'abs_y':2056}">
                    <div class="mg cfx cjm cjq" bis_size="{'x':938,'y':68,'w':122,'h':48,'abs_x':1241,'abs_y':2056}">
                      <p class="text-sm/6 text-gray-900"> ${order.order_date}</p>
                      <p class="mt-1 text-xs/5 text-gray-500">${order.Status}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                      data-slot="icon" class="ol st vg azc"
                      bis_size="{'x':1076,'y':82,'w':20,'h':20,'abs_x':1379,'abs_y':2070}">
                      <path fill-rule="evenodd"
                        d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                        clip-rule="evenodd"   
         bis_size="{'x':1084,'y':87,'w':5,'h':9,'abs_x':1387,'abs_y':2075}"></path>
                    </svg>
                  </div>
                </li>
              `;

        orderTableBody.append(tableRow);
      });
      
      // Event delegation to handle clicks on dynamically added `li.order-item` elements
      $(document).on('click', '.order-item', function () {
        const orderId = $(this).data('order-id');
        $('#drawer').removeClass('translate-x-full').addClass('translate-x-0 sm:duration-700'); // Opens the drawer
        $('#drawerp1').addClass('fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity sm:duration-700');
        
        function openDrawer(orderId) {
          fetch('/Api/getOrders.php')
            .then(response => {
              if (!response.ok) {
                throw new Error(`Network response was not ok: ${response.statusText}`);
              }
              return response.json();
            })
            .then(data => {
              // Find the order by ID
              const order = data.find(order => order.order_id == orderId);
              if (order) {
                // Update drawer content with order details
                document.getElementById('orderTitle').textContent = `Order # ${orderId}`;
                document.getElementById('orderDetails').innerHTML = `
                  <p>Name: <b>${order.name}</b></p>
                  <p>Email: <b>${order.email}</b></p>
                  <p>Location: <b>${order.location}</b></p>
                  <p>Date: <b>${order.order_date}</b></p>
                  <p>Status: <b>${order.Status}</b></p>
                `;

                order.products.forEach(product => {
                    document.getElementById('orderDetails').innerHTML += `
                        <div class="flex items-center gap-x-6" style=" padding: 15px 0; ">
                          <a href="${product.product_link}" target="_blank"><img class="h-16 w-16" src="${product.image_url}" alt="${product.product_name}"></a>
                          <div>
                            <h3 class="text-base/7 font-semibold tracking-tight text-gray-900"><a href="${product.product_link}" target="_blank">${product.product_name}</a></h3>
                            <p class="text-sm/6 font-semibold text-indigo-600"><a href="${product.product_link}" target="_blank">${product.item_number}</a></p>
                          </div>
                        </div>`;
                });
              } else {
                console.error("Order not found");
                document.getElementById('orderDetails').textContent = "Order not found.";
              }
            })
            .catch(error => {
              console.error("There was a problem with the fetch operation:", error);
              document.getElementById('orderDetails').textContent = "Error loading order details.";
            });
        
          // Show the drawer
          drawer.classList.remove('translate-x-full');
        }
        openDrawer(orderId);

      });

      // Close the drawer when the close button is clicked
      $('#closeDrawer').click(function () {
        $('#drawer').removeClass('translate-x-0').addClass('translate-x-full sm:duration-700'); // Closes the drawer
        $('#drawerp1').removeClass('fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity');
      });
    });


  });
</script>

<script src="https://cdn.tailwindcss.com"></script>