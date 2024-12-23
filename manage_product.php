<?php
include 'plugin/head.php';
include 'db.php';  // Make sure db.php contains your mysqli connection details

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add product to the database
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    // Get the form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];

    // Use 'Pending' as default if no status is selected
    $status = isset($_POST['status']) ? $_POST['status'] : 'Pending';  // Default to 'Pending'

    // Handle image upload
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = $_FILES['image']['name'];
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageSize = $_FILES['image']['size'];
        $imageType = $_FILES['image']['type'];
        $uploadDir = 'uploads/';
        $imagePath = $uploadDir . basename($imageName);
        
        // Move uploaded image to server directory
        if (move_uploaded_file($imageTmpName, $imagePath)) {
            $image = $imagePath;
        }
    }

    // Insert new product into the database
    $query = "INSERT INTO `products` (name, description, price, stock_quantity, status, image) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssdiis', $name, $description, $price, $stock_quantity, $status, $image);
    
    if ($stmt->execute()) {
        echo "<script>alert('Product added successfully!'); window.location.href = 'manage_product.php';</script>";
    } else {
        echo "<script>alert('Error adding product.');</script>";
    }
}

// Query to retrieve product data
$query = "SELECT `product_id`, `name`, `description`, `price`, `image`, `stock_quantity`, `status`, `created_at`, `updated_at` FROM `products` WHERE 1";
$result = $conn->query($query);

// Check if query was successful
if ($result->num_rows > 0) {
    // Fetch all results into an array
    $products = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $products = [];
}

$conn->close();  // Close the connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link href="https://fonts.googleapis.com/css2?family=MedievalSharp&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Manage Products</h1>
    <button onclick="openAddModal()" class="add-btn">Add Product</button>
    <table>
        <tr>
            <th>Product ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock Quantity</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td><?php echo htmlspecialchars($product['description']); ?></td>
                <td><?php echo htmlspecialchars($product['price']); ?></td>
                <td><?php echo htmlspecialchars($product['stock_quantity']); ?></td>
                <td><?php echo htmlspecialchars($product['status']); ?></td>
                <td><?php echo htmlspecialchars($product['created_at']); ?></td>
                <td><?php echo htmlspecialchars($product['updated_at']); ?></td>
                <td class="btns">
                    <!-- Edit button -->
                    <a href="#" class="edit-btn" onclick="openModal(<?php echo $product['product_id']; ?>, '<?php echo $product['name']; ?>', '<?php echo $product['description']; ?>', '<?php echo $product['price']; ?>', '<?php echo $product['stock_quantity']; ?>', '<?php echo $product['image']; ?>', '<?php echo $product['status']; ?>')">Edit</a>

                    <!-- Delete button -->
                    <a href="#" class="delete-btn" onclick="deleteProduct(<?php echo $product['product_id']; ?>)">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Modal for Editing -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2>Edit Product</h2>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="product_id" id="product_id">
                <label>Name:</label>
                <input type="text" name="name" id="name" required><br><br>
                
                <label>Description:</label>
                <input type="text" name="description" id="description" required><br><br>

                <label>Price:</label>
                <input type="number" name="price" id="price" required><br><br>

                <label>Stock Quantity:</label>
                <input type="number" name="stock_quantity" id="stock_quantity" required><br><br>

                <label>Status:</label>
                <select name="status" id="status">
                    <option value="Pending">Pending</option>
                    <option value="Confirmed">Confirmed</option>
                    <option value="Cancelled">Cancelled</option>
                    <option value="Shipped">Shipped</option>
                    <option value="Sold">Sold</option>
                </select><br><br>

                <!-- Show current image -->
                <label>Current Image:</label><br>
                <img id="currentImage" src="" alt="Current Product Image" width="100"><br><br>

                <!-- Input for uploading new image -->
                <label>Upload New Image (Optional):</label>
                <input type="file" name="image" id="image"><br><br>

                <button type="submit">Update Product</button>
                <button type="button" class="delete-btn" onclick="deleteProduct()">Delete Product</button>
                <button type="button" onclick="closeModal()">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div id="addProductModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeAddModal()">&times;</span>
            <h2>Add New Product</h2>
            <form id="addForm" method="POST" enctype="multipart/form-data">
                <label>Name:</label>
                <input type="text" name="name" id="new_name" placeholder="Product name" required><br><br>

                <label>Description:</label>
                <input type="text" name="description" id="new_description" placeholder="Describe product" required><br><br>

                <label>Price:</label>
                <input type="number" name="price" id="new_price" placeholder="Product price" required><br><br>

                <label>Stock Quantity:</label>
                <input type="number" name="stock_quantity" id="new_stock_quantity" placeholder="Product quantity" required><br><br>

                <label>Status:</label>
                <select name="status" id="new_status">
                    <option value="Pending">Pending</option>
                    <option value="Confirmed">Confirmed</option>
                    <option value="Cancelled">Cancelled</option>
                    <option value="Shipped">Shipped</option>
                    <option value="Sold">Sold</option>
                </select><br><br>

                <label>Upload Image:</label>
                <input type="file" name="image" id="new_image"><br><br>

                <button type="submit">Add Product</button>
                <button type="button" onclick="closeAddModal()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        // Open Add Product Modal
        function openAddModal() {
            document.getElementById("addProductModal").style.display = "block";
        }

        // Close Add Product Modal
        function closeAddModal() {
            document.getElementById("addProductModal").style.display = "none";
        }

        // Close the modal if clicking outside of the modal content
        window.onclick = function(event) {
            if (event.target == document.getElementById("addProductModal")) {
                closeAddModal();
            }
        }

        // Delete Product
        function deleteProduct(productId) {
            if (confirm('Are you sure you want to delete this product?')) {
                window.location.href = `delete_product.php?product_id=${productId}`;
            }
        }
    </script>
</body>
</html>
