 
 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
 
 <style>/* Header */
    header {
    width: 100%;
    background: #2f2f2f;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    height: 90px;
    padding: 25px;
    display: flex;
    align-items: flex-start;
  top: 0;  /* Aligns the header to the top of the page */
  left: 0;  /* Aligns it to the left corner */
  z-index: 1000; 
    }
   
    nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 2rem;
    align-items: center;
    }

    .nav-logo {
    max-width: 50px;
    max-height: 50px;
    margin-right: 8px;
    vertical-align: middle;
    }

    /* Navigation Menu */
    .menu {
    list-style: none;
    display: flex;
    align-items: center;
    color: white;
    }

    .menu li a {
    text-decoration: none;
    color: white;
    padding: 0.5rem 1rem;
    display: inline-block;
    }

    .menu li a:hover {
    background-color: #ff3d0d;
    color: #fff;
    border-radius: 4px;
    }

    /* Main Content */
    main {
    padding: 2rem;
    }

    /* Container */
    .container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 1rem;
    }

    /* Hamburger Icon */
    .hamburger-menu {
    display: none;
    flex-direction: column;
    justify-content: space-between;
    width: 25px;
    height: 15px;
    cursor: pointer;
    }

    .hamburger-menu span {
    width: 100%;
    height: 3px;
    background-color:rgb(249, 249, 250);
    }

    /* Mobile Navigation Menu */
    .mobile-menu {
    display: none;
    list-style: none;
    position: absolute;
    top: 100%;
    left: 0;
    background-color: #fff;
    width: 100%;
    padding: 1rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    z-index: 999;
    }

    .mobile-menu li {
    margin-bottom: 1rem;
    }

    .mobile-menu li a {
    text-decoration: none;
    color: #111131;
    font-size: 1rem;
    }
    /* Mobile Navigation Menu */
    .mobile-menu {
    display: none;
    list-style: none;
    position: top;
    top: 0; /* Position it at the top */
    left: 0;
    background-color: #fff;
    width: 200px;
    padding: 1rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    z-index: 999;
    }

    /* Adjust the hamburger icon behavior */
    .hamburger-menu.active span:nth-child(1) {
    transform: translateY(3px) rotate(45deg);
    }

    .hamburger-menu.active span:nth-child(2) {
    opacity: 0;
    }

    .hamburger-menu.active span:nth-child(3) {
    transform: translateY(-3px) rotate(-45deg);
    }

    /* Show mobile menu when it's active */
    @media screen and (max-width: 1024px) {
    .hamburger-menu {
        display: flex;
    }
    .menu {
        display: none;
    }
    /* Show mobile menu when it's active */
    .mobile-menu.active {
        display: block;
    }
    }

    /* Article image responsiveness */
    .article-image img {
    width: 100%;
    height: auto;
    max-width: 768px;
    display: block;
    margin: 0 auto;
    }
    </style>
    </head>
 <body>
 <header>
    
    <nav class="container">
        <!-- Logo -->
        <div class="logo">
        
            <span></span>
        </a>
        </div>
        <!-- Navigation Menu -->
        <ul class="menu">
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="manage_users.php">Manage Users</a></li>
        <li><a href="manage_product.php">Manage Products</a></li>
        <li><a href="ad_transactions.php">Transactions</a></li>
        <li><a href="logout.php">Log out</a></li>
        </ul>
        <!-- Hamburger Icon -->
        <div class="hamburger-menu">
        <span></span>
        <span></span>
        <span></span>
        </div>
        <!-- Mobile Navigation Menu -->
        <ul class="mobile-menu">
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="manage_users.php">Manage Users</a></li>
        <li><a href="manage_product.php">Manage Products</a></li>
        <li><a href="ad_transactions.php">Transactions</a></li>
        <li><a href="logout.php">Log out</a></li>
        </ul>
    </nav>
    </header>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
    const hamburgerMenu = document.querySelector('.hamburger-menu');
    const mobileMenu = document.querySelector('.mobile-menu');

    function toggleMenu() {
        hamburgerMenu.classList.toggle('active');
        mobileMenu.classList.toggle('active');
    }

    hamburgerMenu.addEventListener('click', toggleMenu);

    mobileMenu.querySelectorAll('a').forEach(function(menuItem) {
        menuItem.addEventListener('click', toggleMenu);
    });
    });

    </script>
 </body>
 </html>
    
  