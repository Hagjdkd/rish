<?php
include 'plugin/head.php';
include 'db.php';

// Fetch products from the database
$query = "SELECT `product_id`, `name`, `description`, `price`, `image`, `stock_quantity`, `created_at`, `updated_at` FROM `products` WHERE 1";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="des/stey.css">
</head>
<body>

    <!-- Sidebar -->
  

    <!-- Main Content -->
    <div class="main-content">
        <!-- Animated Welcome Message -->
        <div class="welcome-message">
            Welcome to SPO Admin Dashboard
        </div>

        <!-- Cards Container -->
        <div class="cards-container">
            <?php
            // Loop through the fetched products and display them in cards
            if (mysqli_num_rows($result) > 0) {
                while ($product = mysqli_fetch_assoc($result)) {
                    echo '<div class="card">';
                    if ($product['image']) {
                        echo '<img src="' . $product['image'] . '" alt="' . $product['name'] . '">';
                    }
                    echo '<h3>' . $product['name'] . '</h3>';
                    echo '<p>' . $product['description'] . '</p>';
                    echo '<div class="price">₱' . number_format($product['price'], 2) . '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No products found.</p>';
            }
            ?>
        </div>
    </div>
    <footer>
        <?php include 'plugin/footer.php'; ?>
    </footer>

</body>
</html>
