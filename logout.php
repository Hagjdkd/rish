<?php
// Start the session
session_start();

// Destroy the session to log out the user
session_unset(); // Unsets all session variables
session_destroy(); // Destroys the session

// Redirect the user to the login page
header("Location: login.php"); // Change this to your desired page
exit();
?>
