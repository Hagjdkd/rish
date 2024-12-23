<?php
include 'plugin/header.php';
include 'db.php';

$user_id = $_SESSION['user_id'];

// Handle transaction cancellation
if (isset($_POST['cancel_transaction'])) {
    $trans_id_to_cancel = $_POST['trans_id'];

    // Begin a transaction to ensure both operations succeed or fail together
    $conn->begin_transaction();

    try {
        // Update the transaction status to 'Cancelled'
        $cancel_sql = "UPDATE transactions SET status = 'Cancelled' WHERE trans_id = ? AND user_id = ?";
        $stmt = $conn->prepare($cancel_sql);
        $stmt->bind_param("ii", $trans_id_to_cancel, $user_id);
        $stmt->execute();

        // Remove associated products from transaction_products table
        $delete_products_sql = "DELETE FROM transaction_products WHERE trans_id = ?";
        $stmt = $conn->prepare($delete_products_sql);
        $stmt->bind_param("i", $trans_id_to_cancel);
        $stmt->execute();

        // Commit the transaction
        $conn->commit();

        echo "<script>alert('Transaction cancelled and products removed successfully!'); window.location.href='transactions.php';</script>";
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $conn->rollback();
        echo "<script>alert('Failed to cancel transaction: {$e->getMessage()}');</script>";
    }
}

// Fetch transactions and their products
$sql = "
    SELECT t.trans_id, t.user_id, t.price, t.status, t.transaction_date, 
           t.shipping_address, t.contact_number, 
           tp.product_id, tp.quantity, tp.price as product_price, 
           p.name as product_name, p.image as product_image
    FROM transactions t
    JOIN transaction_products tp ON t.trans_id = tp.trans_id
    JOIN products p ON tp.product_id = p.product_id
    WHERE t.user_id = ? AND t.status != 'Cancelled'
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('uploads/liberator3.jpg') no-repeat center center/cover;
            margin: 0;
            padding: 0;
        }
        h2 {
            text-align: center;
            color: orange;
            padding: 20px 0;
            margin: 0;
        }
        .transaction-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }
        .transaction-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            padding: 15px;
            transition: transform 0.3s ease-in-out;
        }
        .transaction-card:hover {
            transform: translateY(-10px);
        }
        .transaction-card h3 {
            margin: 0;
            color: #2e3b4e;
        }
        .transaction-card p {
            margin: 10px 0;
            color: #555;
        }
        .product-info {
            margin-top: 10px;
        }
        .product-info img {
            width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .product-info p {
            font-size: 14px;
        }
        .price {
            font-weight: bold;
            color: #2e3b4e;
        }
        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            font-size: 14px;
            color: #888;
        }
        .cancel-button {
            padding: 5px 10px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .cancel-button:hover {
            background-color: darkred;
        }
        |
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
</head>
<body>
    <h2>Your Transactions</h2>
    <div class="transaction-container">
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="transaction-card">
    <h3>Transaction ID: <?= $row['trans_id'] ?></h3>
    <p><strong>Date:</strong> <?= $row['transaction_date'] ?></p>
    <p><strong>Status:</strong> 
        <?php 
        if ($row['status'] == 'Confirmed') {
            echo 'In Transit'; 
        } else {
            echo $row['status'];
        }
        ?>
    </p>
    <p><strong>Total Price:</strong> $<?= number_format($row['price'], 2) ?></p>
    <p><strong>Shipping Address:</strong> <?= $row['shipping_address'] ?></p>
    <p><strong>Contact Number:</strong> <?= $row['contact_number'] ?></p>
    <div class="product-info">
        <h4>Products:</h4>
        <img src="<?= htmlspecialchars($row['product_image']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
        <p><strong>Product Name:</strong> <?= htmlspecialchars($row['product_name']) ?></p>
        <p class="price">Price: $<?= number_format($row['product_price'], 2) ?></p>
        <p>Quantity: <?= $row['quantity'] ?></p>
    </div>
    <div class="card-footer">
        <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this transaction?');">
            <input type="hidden" name="trans_id" value="<?= $row['trans_id'] ?>">
            <button type="submit" name="cancel_transaction" class="cancel-button">Cancel</button>
        </form>
    </div>
</div>

        <?php endwhile; ?>
    </div>
    <div class="nav-buttons">
                <a href="cart.php">Previous</a>
            </div>
            <div class="nav-buttons">
                <a href="spo_dashboard.php">Next</a>
            </div>
</body>
</html>
