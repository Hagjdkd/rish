<?php
include 'db.php';  // Make sure db.php contains your mysqli connection details

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID from URL
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;

// Delete query
$query = "DELETE FROM `users` WHERE `user_id` = $user_id";
if ($conn->query($query) === TRUE) {
    echo "User deleted successfully.";
    header("Location: manage_users.php"); // Redirect back to the user management page
} else {
    echo "Error deleting user: " . $conn->error;
}

$conn->close();
?>
