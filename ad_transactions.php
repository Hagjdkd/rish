<?php
include 'plugin/head.php';
// Include database connection
include('db.php');

// Fetch transactions with user details
$sql_transactions = "
    SELECT t.`trans_id`, t.`user_id`, t.`price`, t.`status`, t.`transaction_date`, 
           t.`shipping_address`, t.`contact_number`, u.`username` AS user_name
    FROM `transactions` t
    JOIN `users` u ON t.`user_id` = u.`user_id`
";
$result_transactions = mysqli_query($conn, $sql_transactions);

// Fetch transaction products
$sql_transaction_products = "
    SELECT tp.`trans_product_id`, tp.`trans_id`, tp.`product_id`, tp.`quantity`, tp.`price`
    FROM `transaction_products` tp
";
$result_transaction_products = mysqli_query($conn, $sql_transaction_products);

// Organize data into an associative array by trans_id
$transaction_data = [];
while ($row = mysqli_fetch_assoc($result_transaction_products)) {
    $transaction_data[$row['trans_id']][] = $row;
}

// Handle actions (delete and update status)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        $trans_id = $_POST['trans_id'];
        $sql_delete_products = "DELETE FROM `transaction_products` WHERE `trans_id` = '$trans_id'";
        mysqli_query($conn, $sql_delete_products);
        $sql_delete_transaction = "DELETE FROM `transactions` WHERE `trans_id` = '$trans_id'";
        mysqli_query($conn, $sql_delete_transaction);
    }

    if (isset($_POST['update_status'])) {
        $trans_id = $_POST['trans_id'];
        $new_status = $_POST['status'];
        $sql_update_status = "UPDATE `transactions` SET `status` = '$new_status' WHERE `trans_id` = '$trans_id'";
        mysqli_query($conn, $sql_update_status);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Transactions</title>
    <link rel="stylesheet" href="style.css"> <!-- Optional: Include CSS for styling -->
</head>
<body>

<h1>Admin Transactions</h1>

<table border="1">
    <thead>
        <tr>
            <th>Transaction ID</th>
            <th>User Name</th>
            <th>User ID</th>
            <th>Price</th>
            <th>Status</th>
            <th>Transaction Date</th>
            <th>Shipping Address</th>
            <th>Contact Number</th>
            <th>Products</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($transaction = mysqli_fetch_assoc($result_transactions)) : ?>
            <tr>
                <td><?php echo $transaction['trans_id']; ?></td>
                <td><?php echo htmlspecialchars($transaction['user_name']); ?></td>
                <td><?php echo $transaction['user_id']; ?></td>
                <td><?php echo $transaction['price']; ?></td>
                <td><?php echo $transaction['status']; ?></td>
                <td><?php echo $transaction['transaction_date']; ?></td>
                <td><?php echo $transaction['shipping_address']; ?></td>
                <td><?php echo $transaction['contact_number']; ?></td>
                <td>
                    <?php
                    // Check if there are products for this transaction ID
                    if (isset($transaction_data[$transaction['trans_id']])) {
                        foreach ($transaction_data[$transaction['trans_id']] as $product) {
                            echo "Product ID: " . $product['product_id'] . "<br>";
                            echo "Quantity: " . $product['quantity'] . "<br>";
                            echo "Price: " . $product['price'] . "<br><br>";
                        }
                    } else {
                        echo "No products found";
                    }
                    ?>
                </td>
                <td class="btns">
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="trans_id" value="<?php echo $transaction['trans_id']; ?>">
                        <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this transaction?');">Delete</button>
                    </form>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="trans_id" value="<?php echo $transaction['trans_id']; ?>">
                        <select name="status" class="delete-btn">
                            <option value="Pending" <?php echo ($transaction['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="Confirmed" <?php echo ($transaction['status'] == 'Confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                            <option value="Shipped" <?php echo ($transaction['status'] == 'Shipped') ? 'selected' : ''; ?>>Shipped</option>
                            <option value="Completed" <?php echo ($transaction['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                            <option value="Cancelled" <?php echo ($transaction['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                        <button type="submit" name="update_status">Update Status</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
