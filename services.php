<?php 
include 'plugin/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services</title>
    <link rel="stylesheet" href="for_all.css">
</head>
<body>

<!-- Services Section -->
<div class="container" id="services">
    <section class="services-content">
        <h2>Our Services</h2>
        <p>We offer a range of high-quality services to help you achieve your goals. Explore our offerings below:</p>
        
        <!-- Services List -->
        <div class="services-list">
            <div class="service-item">
                <img src="uploads/liberator4.jpg" alt="Service 1 Icon">
                <h3>Service 1</h3>
                <p>Description of Service 1 goes here. This can include a short paragraph or a bullet point list of benefits.</p>
            </div>
            <div class="service-item">
                <img src="uploads/liberator4.jpg" alt="Service 2 Icon">
                <h3>Service 2</h3>
                <p>Description of Service 2 goes here. Provide more details about what this service entails and how it benefits customers.</p>

            </div>
            <div class="service-item">
                <img src="uploads/liberator4.jpg" alt="Service 3 Icon">
                <h3>Service 3</h3>
                <p>Description of Service 3 goes here. Briefly explain what this service offers and why it’s valuable.</p>

            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="nav-buttons">
            <a href="contact_us.php">Previous</a> <!-- Redirects to contact_us.php -->
            <a href="gallery.php">Next</a> <!-- Redirects to gallery.php -->
        </div>
    </section>
</div>

<!-- Include the footer -->
<?php include 'plugin/footer.php'; ?>

</body>
</html>
