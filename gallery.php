<?php
// Include the header
include 'plugin/header.php';
include 'db.php';

// SQL query to get products
$sql = "SELECT product_id, name, description, price, image, stock_quantity, status, created_at, updated_at FROM products WHERE 1";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=MedievalSharp&display=swap" rel="stylesheet">
    <title>Gallery - E-commerce</title>
    <style>
        body {
            font-family: 'MedievalSharp', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('uploads/liberator3.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .gallery-content {
            text-align: center;
        }

        .gallery-content h2 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #333;
        }

        .gallery-images {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
/* ... other styles ... */

.product {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s, box-shadow 0.2s;
    position: relative;
    height: 350px; /* Fixed height for the product card */
    display: flex; /*Enable flexbox for better image handling*/
    flex-direction: column; /*Stack image and details vertically*/
}

.product img {
    width: 100%;
    height: 120px;
    object-fit: cover; /* Cover the entire container */
    flex: 1; /* Allow the image to take up available space while respecting aspect ratio*/
    cursor: zoom-in;
}

/* ... rest of the styles ... */
        .product-details {
            padding: 15px;
            text-align: left;
        }

        .product-details p {
            margin: 5px 0;
            color: #555;
            font-size: 1em;
        }

        .product-details p:first-child {
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
        }

        .cta-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .cta-btn:hover {
            background-color: #0056b3;
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

        /* Zoom Styles */
        .zoomed-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8); /* Dimmed background */
            display: none; /* Hidden by default */
            justify-content: center;
            align-items: center;
            z-index: 1000; /* Ensure it's on top */
        }

        .zoomed-image img {
            max-width: 90%;
            max-height: 90%;
        }

        .close-zoom {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 2em;
            color: white;
            cursor: pointer;
        }

        @media (max-width: 600px) {
            .gallery-content h2 {
                font-size: 1.8em;
            }

            .product img {
                height: 150px;
            }
        }
    </style>
</head>
<body>

    <!-- Gallery Section -->
    <div class="container" id="gallery">
        <section class="gallery-content">
            <h2>Pieces Made from Scratch</h2>
            <div class="gallery-images">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="product">
                            <img src="<?= htmlspecialchars($row["image"]) ?>" alt="<?= htmlspecialchars($row["name"]) ?>">
                            <div class="product-details">
                                <p><?= htmlspecialchars($row["name"]) ?></p>
                                <p>Crafted by <?= htmlspecialchars($row["description"]) ?></p>
                                <p>$<?= number_format($row["price"], 2) ?></p>
                                <p>Quantity: <?= htmlspecialchars($row["stock_quantity"]) ?></p>
                                <p>Status: <?= htmlspecialchars($row["status"]) ?></p>
                                <a href="add_to_cart.php?add=<?= $row["product_id"] ?>" class="cta-btn">Add to Cart</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No products available at the moment.</p>
                <?php endif; ?>
            </div>

            <!-- Navigation Buttons -->
            <div class="nav-buttons">
                <a href="services.php">Previous</a>
            </div>
            <div class="nav-buttons">
                <a href="cart.php">Next</a>
            </div>
        </section>
    </div>

    <!-- Zoomed Image Overlay -->
    <div class="zoomed-image">
        <span class="close-zoom">&times;</span>
        <img src="" alt="Zoomed Image">
    </div>


    <!-- Include the footer -->
    <?php include 'plugin/footer.php'; ?>

    <script>
        const images = document.querySelectorAll('.product img');
        const zoomedImage = document.querySelector('.zoomed-image img');
        const zoomedOverlay = document.querySelector('.zoomed-image');
        const closeZoom = document.querySelector('.close-zoom');

        images.forEach(img => {
            img.addEventListener('click', () => {
                zoomedImage.src = img.src;
                zoomedOverlay.style.display = 'flex';
            });
        });

        closeZoom.addEventListener('click', () => {
            zoomedOverlay.style.display = 'none';
        });

        zoomedOverlay.addEventListener('click', (event) => {
            if (event.target === zoomedOverlay) {
                zoomedOverlay.style.display = 'none';
            }
        });
    </script>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>