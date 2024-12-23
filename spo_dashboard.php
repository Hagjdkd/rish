<?php
include 'plugin/header.php';

// Include database connection
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=MedievalSharp&display=swap" rel="stylesheet">
    <title>SPO Dashboard</title>
    <link rel="stylesheet" href="ris.css">
</head>
<body>
    <!-- Hero Section -->
    <section id="hero">
        <h1>THE LIBERATOR</h1>
        <p>Unveiling truths; empowering minds.</p>
        <!-- Button Section -->
        <div class="button-grid">
            <a href="about_us.php" class="button-item">
                <img src="uploads/AboutUs.jpg" alt="About Us Logo">
                <span>About Us</span>
            </a>
            <a href="contact_us.php" class="button-item">
                <img src="uploads/liberator14.jfif" alt="Contact Us Logo">
                <span>Contact Us</span>
            </a>
            <a href="services.php" class="button-item">
                <img src="uploads/liberator11.jpg" alt="Services Logo">
                <span>Services</span>
            </a>
            <a href="gallery.php" class="button-item">
                <img src="uploads/liberator3.jpg" alt="Gallery Logo">
                <span>Gallery</span>
            </a>
            <a href="cart.php" class="button-item">
                <img src="uploads/liberator3.jpg" alt="Gallery Logo">
                <span>Cart</span>
            </a>
            <a href="transactions.php" class="button-item">
                <img src="uploads/liberator3.jpg" alt="Gallery Logo">
                <span>Transactions</span>
            </a>
        </div>
    </section>
    <!-- Footer -->
    <footer>
        <?php include 'plugin/footer.php'; ?>
    </footer>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
