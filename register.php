<?php 
// Include the database connection file (create this file to handle DB connection)
include 'db.php'; // This will have your DB connection code

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form input values
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $full_name = $_POST['full_name'];
    $role = $_POST['role']; // This should be 'user' or 'admin'

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Create a SQL query to insert the user into the database
    $query = "INSERT INTO users (username, email, password_hash, full_name, role) 
              VALUES (?, ?, ?, ?, ?)";

    // Prepare the SQL statement
    if ($stmt = $conn->prepare($query)) {
        // Bind parameters to the SQL query
        $stmt->bind_param("sssss", $username, $email, $password_hash, $full_name, $role);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect to login page after successful registration
            header("Location: login.php");
            exit(); // Stop further script execution
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "Error preparing query: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #2f2f2f;
            background-image: url('uploads/liberator3.jpg'); /* Add your background image URL here */
            background-size: cover; /* Ensures the image covers the entire container */
            background-position: center; /* Centers the background image */
            background-repeat: no-repeat; /* Prevents the image from repeating */
            height: 100vh; /* Ensures it takes full viewport height */
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-container {
            background-color: rgba(0, 0, 0, 0.6); /* Semi-transparent background */
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 700px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 2rem;
            color: white; /* Changed header color to white */
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 1rem;
            margin-bottom: 5px;
            font-weight: bold;
            color: white; /* Set label text color to white */
        }

        input[type="text"], input[type="email"], input[type="password"], select {
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            width: 100%;
        }

        input[type="submit"] {
            padding: 12px;
            background-color: #ffb347; /* Pastel orange color */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #e79f29; /* Slightly darker shade of orange */
        }

        .already-account {
            text-align: center;
            margin-top: 20px;
            font-size: 1rem;
        }

        .already-account a {
            text-decoration: none;
            color: #ff9f00;
            font-weight: bold;
        }

        .already-account a:hover {
            color: #e68900;
        }
    </style>
</head>
<body>

<!-- Register Form Container -->
<div class="register-container">
    <h2>Register</h2>

    <form action="register.php" method="POST">
        <!-- Username -->
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <!-- Email -->
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <!-- Password -->
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <!-- Full Name -->
        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" required><br>

        <!-- Role (optional, default to user) -->
        <label for="role">Role:</label>
        <select id="role" name="role">
            <option value="user" selected>User</option>
            <option value="admin">Admin</option>
        </select><br>

        <!-- Submit Button -->
        <input type="submit" value="Register">
    </form>

    <p class="already-account">Already have an account? <a href="login.php">Login here</a></p>
</div>

</body>
</html>
