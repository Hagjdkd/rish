<?php
// Include the database connection
include 'db.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect if not logged in
    exit();
}

// Check if product ID is passed and if the add action is triggered
if (isset($_GET['add'])) {
    $product_id = $_GET['add'];
    $user_id = $_SESSION['user_id']; // Get the logged-in user ID

    // Query to check if product is already in the cart
    $query = "SELECT * FROM cart_items WHERE user_id = ? AND product_id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // If the product is not already in the cart, add it
        if ($result->num_rows == 0) {
            // Insert the product into the cart
            $insert_query = "INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, 1)";
            if ($insert_stmt = $conn->prepare($insert_query)) {
                $insert_stmt->bind_param("ii", $user_id, $product_id);
                $insert_stmt->execute();
                echo "Product added to cart!";
                header("Location: cart.php"); // Redirect to cart page after adding
                exit();
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "Product is already in your cart.";
            header("Location: cart.php"); // Redirect to cart page
            exit();
        }
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
