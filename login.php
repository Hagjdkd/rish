<?php
// Include the database connection file
include 'db.php';

// Start the session
session_start();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form input values and sanitize them
    $username_or_email = trim($_POST['username_or_email']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($username_or_email) || empty($password)) {
        $error_message = "Both username/email and password are required.";
    } else {
        // SQL query to fetch user details based on username or email
        $query = "SELECT user_id, username, email, password_hash, role FROM users WHERE username = ? OR email = ?";

        // Prepare and execute the query
        if ($stmt = $conn->prepare($query)) {
            // Bind parameters
            $stmt->bind_param("ss", $username_or_email, $username_or_email);

            if ($stmt->execute()) {
                // Get the result
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                    // Fetch the user data
                    $user = $result->fetch_assoc();

                    // Verify the password
                    if (password_verify($password, $user['password_hash'])) {
                        // Store user information in the session
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['role'] = $user['role'];  // Store the user's role

                        // Redirect to the appropriate dashboard based on role
                        if ($_SESSION['role'] == 'admin') {
                            header("Location: admin_dashboard.php");
                        } else {
                            header("Location: spo_dashboard.php");
                        }
                        exit();
                    } else {
                        $error_message = "Invalid password.";
                    }
                } else {
                    $error_message = "No user found with the given username or email.";
                }
            } else {
                $error_message = "Error executing query: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $error_message = "Error preparing query: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=MedievalSharp&display=swap" rel="stylesheet">
    <style>
        /* Resetting some base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styling with background image */
        body {
            font-family: 'MedievalSharp', cursive;
            background-image: url('uploads/liberator3.jpg'); /* Setting background image */
            background-size: cover; /* Ensures the image covers the entire page */
            background-position: center; /* Centers the image */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: white; /* White text to contrast with background */
        }

        /* Container for the form */
        .form-container {
            background-color: rgba(0, 0, 0, 0.7); /* Slightly transparent black background */
            padding: 40px; /* Increased padding for larger content */
            border-radius: 12px; /* Slightly rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px; /* Increased the max-width for larger container */
            text-align: center;
        }

        /* Heading Styling */
        .form-container h2 {
            font-size: 2.2rem; /* Increased heading size */
            margin-bottom: 20px;
            color: white; /* Ensure the heading text is white */
        }

        /* Error Message Styling */
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        /* Label Styling */
        label {
            font-size: 16px;
            margin-bottom: 5px;
            display: block;
            color: #ddd; /* Light color for better visibility on dark background */
        }

        /* Input Fields Styling */
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background-color: rgba(255, 255, 255, 0.8); /* Slightly transparent white for input fields */
        }

        /* Submit Button Styling */
        input[type="submit"] {
            background-color: #333;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        /* Hover Effect for Submit Button */
        input[type="submit"]:hover {
            background-color: #555;
        }

        /* Register Link Styling */
        .register-link {
            margin-top: 20px;
            font-size: 16px;
            color: #ddd;
        }

        .register-link a {
            color: #ff9f00; /* Orange color for the register link */
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .form-container {
                padding: 25px;
                width: 90%;
            }

            .form-container h2 {
                font-size: 1.8rem;
            }

            input[type="submit"] {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Login</h2>
        
        <!-- Display error message if there's one -->
        <?php
        if (!empty($error_message)) {
            echo '<div class="error-message">' . htmlspecialchars($error_message) . '</div>';
        }
        ?>
        
        <form action="login.php" method="POST">
            <label for="username_or_email">Username or Email</label>
            <input type="text" id="username_or_email" name="username_or_email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Login">
        </form>

        <!-- Register link -->
        <div class="register-link">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>

</body>
</html>
