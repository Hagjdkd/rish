<?php 
include 'plugin/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
  <link rel="stylesheet" href="for_all.css">
</head>
<body>

<!-- Contact Form Section -->
<div class="container" id="contact">
    <section class="contact-content">
        <h2>Contact Us</h2>
        <p>If you have any inquiries, feel free to reach out!</p>
        <form action="" method="POST">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" placeholder="Your Message" required></textarea>
            <button type="submit">Submit</button>
        </form>
        <?php if (isset($response)) echo "<p>$response</p>"; ?>

        <!-- Navigation Buttons -->
        <div class="nav-buttons">
            <a href="about_us.php">Previous</a> <!-- Redirects to about_us.php -->
            <a href="services.php">Next</a> <!-- Redirects to services.php -->
        </div>
    </section>
</div>

<!-- Include the footer -->
<?php include 'plugin/footer.php'; ?>

</body>
</html>
