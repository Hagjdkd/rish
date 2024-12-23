<?php
// Include the database connection and start the session
include 'plugin/header.php';
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the user ID from session
$user_id = $_SESSION['user_id'];

// SQL to fetch the products added to the cart
$sql = "SELECT c.`cart_item_id`, c.`user_id`, c.`product_id`, c.`quantity`, c.`added_at`, p.`name`, p.`image`, p.`price`
        FROM `cart_items` c
        JOIN `products` p ON c.product_id = p.product_id
        WHERE c.`user_id` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle delete item request from the cart
if (isset($_POST['delete_item_id'])) {
    $delete_item_id = $_POST['delete_item_id'];
    $delete_sql = "DELETE FROM cart_items WHERE product_id = ? AND user_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("ii", $delete_item_id, $user_id);
    $delete_stmt->execute();
    header("Location: cart.php"); // Redirect after deletion
    exit();
}

// Handle order item request
if (isset($_POST['order_item_id'])) {
    $order_item_id = $_POST['order_item_id'];
    $shipping_address = $_POST['shipping_address'];
    $contact_number = $_POST['contact_number'];
    $status = 'Pending';
    $transaction_date = date('Y-m-d H:i:s');

    // Insert the transaction into the database
    $insert_transaction_sql = "INSERT INTO transactions (user_id, price, status, transaction_date, shipping_address, contact_number) 
                                VALUES (?, 0, ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_transaction_sql);
    $insert_stmt->bind_param("issss", $user_id, $status, $transaction_date, $shipping_address, $contact_number);
    $insert_stmt->execute();

    // Get the transaction ID of the inserted record
    $trans_id = $conn->insert_id;

    // Fetch product details from the cart for this order item
    $cart_sql = "SELECT p.price, c.quantity FROM cart_items c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = ? AND c.product_id = ?";
    $cart_stmt = $conn->prepare($cart_sql);
    $cart_stmt->bind_param("ii", $user_id, $order_item_id);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();

    if ($cart_result->num_rows > 0) {
        $cart_item = $cart_result->fetch_assoc();
        $total_amount = $cart_item['price'] * $cart_item['quantity'];

        // Insert the product into the transaction_products table
        $insert_product_sql = "INSERT INTO transaction_products (trans_id, product_id, quantity, price) 
                               VALUES (?, ?, ?, ?)";
        $insert_product_stmt = $conn->prepare($insert_product_sql);
        $insert_product_stmt->bind_param("iiid", $trans_id, $order_item_id, $cart_item['quantity'], $total_amount);
        $insert_product_stmt->execute();

        // Update the transaction price
        $update_transaction_price = "UPDATE transactions SET price = price + ? WHERE trans_id = ?";
        $update_price_stmt = $conn->prepare($update_transaction_price);
        $update_price_stmt->bind_param("di", $total_amount, $trans_id);
        $update_price_stmt->execute();

        // Remove the item from the cart
        $delete_item_sql = "DELETE FROM cart_items WHERE product_id = ? AND user_id = ?";
        $delete_item_stmt = $conn->prepare($delete_item_sql);
        $delete_item_stmt->bind_param("ii", $order_item_id, $user_id);
        $delete_item_stmt->execute();

        echo "<script>
                alert('Your order for the item has been placed successfully.');
                window.location.href = 'cart.php';
              </script>";
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=MedievalSharp&display=swap" rel="stylesheet">
    <title>Checkout - E-commerce</title>
    <style>
        body {
            font-family: 'MedievalSharp', sans-serif;
            background: url('uploads/liberator3.jpg') no-repeat center center/cover;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 50px;
        }
        .product-summary {
            background-color: #fff;
            margin: 15px 0;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 8px;
        }
        .product-summary img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
        .product-summary-details {
            margin-left: 20px;
            flex: 1;
        }
        .product-summary-details h4 {
            margin: 0;
        }
        .button-group {
            display: flex;
            flex-direction: column;
        }
        .button-group button {
            margin: 5px 0;
            background-color: #FAD02E;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

.nav-buttons {
    margin-top: 30px;
    text-align: center;
}

.nav-buttons a {
    display: inline-block;
    padding: 10px 20px;
    margin: 0 10px;
    color: #fff;
    background-color: #FAD02E;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.nav-buttons a:hover {
    background-color: #555;
}

    </style>
    <script>
        function openModal(productId) {
            document.getElementById('modal-' + productId).style.display = 'block';
        }

        function closeModal(productId) {
            document.getElementById('modal-' + productId).style.display = 'none';
        }
    </script>
</head>
<body>
<div class="container">
    <h2>Your Cart</h2>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product-summary">
                <img src="<?= $row['image'] ?>" alt="<?= $row['name'] ?>">
                <div class="product-summary-details">
                    <h4><?= $row['name'] ?></h4>
                    <p>Price: $<?= number_format($row['price'], 2) ?></p>
                    <p>Quantity: <?= $row['quantity'] ?></p>
                    <p>Added on: <?= $row['added_at'] ?></p>
                </div>
                <div class="button-group">
                    <form method="POST">
                        <input type="hidden" name="delete_item_id" value="<?= $row['product_id'] ?>">
                        <button type="submit">Delete</button>
                    </form>
                    <button onclick="openModal(<?= $row['product_id'] ?>)">Place Order</button>
                </div>
            </div>

            <!-- Modal for Place Order -->
            <div id="modal-<?= $row['product_id'] ?>" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal(<?= $row['product_id'] ?>)">&times;</span>
                    <h3>Place Order for <?= $row['name'] ?></h3>
                    <form method="POST">
                        <input type="hidden" name="order_item_id" value="<?= $row['product_id'] ?>">
                        <label for="shipping_address">Shipping Address:</label>
                        <input type="text" name="shipping_address" required>
                        <label for="contact_number">Contact Number:</label>
                        <input type="text" name="contact_number" required>
                        <button type="submit">Confirm Order</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
    <div class="nav-buttons">
                <a href="gallery.php">Previous</a>
            </div>
            <div class="nav-buttons">
                <a href="transactions.php">Next</a>
            </div>
</div>
</body>
</html>
