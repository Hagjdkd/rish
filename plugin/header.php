<?php session_start();?>
<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=MedievalSharp&display=swap" rel="stylesheet">
    <title>DASHBOARD</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'MedievalSharp', sans-serif;
            background-color: white;
            overflow-x: hidden;
            transition: background-color 0.3s ease-in-out;
        }

        header {
            background: #2f2f2f;
            font-family: 'MedievalSharp', sans-serif;
            color: white;
            padding: 4px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
        }

        header h1 {
            font-size: 1.8rem;
        }

        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            padding: 0;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: #333;
        }

        /* Styling for the logout link */
        .logout-link {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            margin-left: 20px;
            transition: color 0.3s ease;
        }

        .logout-link:hover {
            color: #ff9f00; /* Orange color on hover */
        }
    </style>
</head>
<body>

<header>
    <!-- Home link on the left side -->
    <a href="spo_dashboard.php" class="logout-link" onclick="showSection('hero')">Home</a>
    
    <!-- Title and Navigation links centered -->
    <h1>School Publication Office</h1>
    <nav>
        <ul>
            <li><a href="logout.php" onclick="confirmLogout()">Log Out</a></li> <!-- Log Out link to the right -->
        </ul>
    </nav>
</header>

<script>
    // Function to ask for confirmation before logging out
    function confirmLogout() {
        // Display confirmation dialog
        var result = confirm("Are you sure you want to log out?");
        
        // If user confirms, redirect to logout.php
        if (result) {
            window.location.href = "logout.php"; // Redirects to logout.php
        }
    }
</script>

</body>
</html>
