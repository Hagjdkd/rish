<?php
include 'db.php';  // Make sure db.php contains your mysqli connection details

// Get form data
$product_id = $_POST['product_id'];
$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];
$stock_quantity = $_POST['stock_quantity'];
$image = $_FILES['image']['name'];  // Get the uploaded image

// If a new image was uploaded, move it to the 'images' directory
if ($image) {
    $image_path = 'images/' . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
} else {
    // Use the existing image if no new image is uploaded
    $image_path = $_POST['existing_image'];  // Make sure this value is passed if no new image is uploaded
}

// Update the product in the database
$query = "UPDATE products SET name = ?, description = ?, price = ?, stock_quantity = ?, image = ? WHERE product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssdiss", $name, $description, $price, $stock_quantity, $image_path, $product_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Product updated successfully!";
} else {
    echo "Failed to update product.";
}

$stmt->close();
$conn->close();
?>
