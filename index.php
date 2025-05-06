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
  <title>Sahara Home Care - Office Supply Portal</title>
  <link rel="icon" href="https://saharahomecare.com/wp-content/uploads/2024/03/cropped-shc-favicon-32x32.png" sizes="32x32" />
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="cus.css">
  <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-straight/css/uicons-solid-straight.css">
  <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css">
  <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-bold-rounded/css/uicons-bold-rounded.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
  <!-- Mobile Drawer Navigation -->
  <div class="drawer" id="mobileNav">
    <div class="drawer-header">
      <div class="logo-container">
        <img src="https://saharahomecare.com/wp-content/uploads/2024/03/Sahara-Homecare-Logo-01.png" alt="Sahara Home Care Logo">
      </div>
      <button class="drawer-close" id="drawerClose">
        <i class="fi fi-br-cross"></i>
      </button>
    </div>
    <div class="drawer-content">
      <ul class="drawer-nav">
        <li><a href="#" id="drawerDashboardLink" class="active"><i class="fi fi-ss-dashboard"></i> Dashboard</a></li>
        <li><a href="#" id="drawerOrdersLink"><i class="fi fi-ss-list"></i> Orders</a></li>
        <li><a href="#" id="drawerNewOrderLink"><i class="fi fi-ss-plus"></i> New Order</a></li>
        <li><a href="#" id="drawerProductsLink"><i class="fi fi-ss-box"></i> Products</a></li>
        <?php if(in_array($_SESSION['email'], ['youssefa@saharahomecare.com', 'armaghan@saharahomecare.com', 'portalinquiries@saharahomecare.com', 'fahadk@saharahomecare.com'])): ?>
        <li><a href="#" id="drawerAdminLink"><i class="fi fi-ss-admin-alt"></i> Admin</a></li>
        <?php endif; ?>
        <li><a href="logout.php"><i class="fi fi-ss-sign-out"></i> Logout</a></li>
      </ul>
    </div>
  </div>
  <div class="drawer-backdrop" id="drawerBackdrop"></div>

  <!-- Main Header -->
  <header class="modern-header">
    <div class="header-container">
      <div class="logo-container">
        <img src="https://saharahomecare.com/wp-content/uploads/2024/03/Sahara-Homecare-Logo-01.png" alt="Sahara Home Care Logo">
        <div class="logo-text">
          <h1>Office Supply Portal</h1>
          <span>Sahara Home Care</span>
        </div>
      </div>
      
      <nav class="main-nav">
        <button class="mobile-nav-toggle" id="mobileNavToggle">
          <i class="fi fi-ss-menu-burger"></i>
        </button>
        <ul class="nav-links">
          <li><a href="#" id="dashboardLink" class="active"><i class="fi fi-ss-dashboard"></i> Dashboard</a></li>
          <li><a href="#" id="ordersLink"><i class="fi fi-ss-list"></i> Orders</a></li>
          <li><a href="#" id="newOrderLink"><i class="fi fi-ss-plus"></i> New Order</a></li>
        </ul>
      </nav>
      
      <div class="header-right">
        <div class="search-container">
          <span class="search-icon"><i class="fi fi-ss-search"></i></span>
          <input type="search" placeholder="Search..." id="headerSearch">
        </div>
        
        <div class="user-menu">
          <button class="user-button" id="userMenuToggle">
            <div class="user-avatar">
              <?php echo substr($_SESSION['name'], 0, 1); ?>
            </div>
            <div class="user-info">
              <div class="user-name"><?php echo $_SESSION['name']; ?></div>
              <div class="user-role"><?php echo $_SESSION['loca']; ?></div>
            </div>
          </button>
          <!-- User dropdown menu to be added with JavaScript -->
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content Area -->
  <main class="main-content">
    <div id="mainContent">
      <!-- Dynamic content will be loaded here -->
    </div>
  </main>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="app.js"></script>
  <script>
    // Mobile drawer functionality
    const mobileNavToggle = document.getElementById('mobileNavToggle');
    const mobileNav = document.getElementById('mobileNav');
    const drawerBackdrop = document.getElementById('drawerBackdrop');
    const drawerClose = document.getElementById('drawerClose');
    
    mobileNavToggle.addEventListener('click', () => {
      mobileNav.classList.add('open');
      drawerBackdrop.classList.add('open');
      document.body.style.overflow = 'hidden';
    });
    
    const closeDrawer = () => {
      mobileNav.classList.remove('open');
      drawerBackdrop.classList.remove('open');
      document.body.style.overflow = '';
    };
    
    drawerClose.addEventListener('click', closeDrawer);
    drawerBackdrop.addEventListener('click', closeDrawer);
    
    // Sync active state between mobile and desktop navigation
    const navLinks = document.querySelectorAll('.nav-links a');
    const drawerLinks = document.querySelectorAll('.drawer-nav a');
    
    function setActiveLink(linkId) {
      // Remove active class from all links
      navLinks.forEach(link => link.classList.remove('active'));
      drawerLinks.forEach(link => link.classList.remove('active'));
      
      // Add active class to clicked link and its corresponding link
      document.getElementById(linkId).classList.add('active');
      const drawerLinkId = 'drawer' + linkId.charAt(0).toUpperCase() + linkId.slice(1);
      if (document.getElementById(drawerLinkId)) {
        document.getElementById(drawerLinkId).classList.add('active');
      }
    }
    
    // Add click handlers to navigation links
    navLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        setActiveLink(link.id);
      });
    });
    
    drawerLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        if (link.id !== 'drawerClose') {
          e.preventDefault();
          const desktopLinkId = link.id.replace('drawer', '').toLowerCase();
          setActiveLink(desktopLinkId + 'Link');
          closeDrawer();
        }
      });
    });
  </script>
</body>
</html>