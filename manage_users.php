  <?php
  include 'plugin/head.php';
  include 'db.php';  // Make sure db.php contains your mysqli connection details

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  // Query to retrieve user data
  $query = "SELECT `user_id`, `username`, `email`, `password_hash`, `full_name`, `role`, `created_at`, `updated_at` FROM `users` WHERE 1";
  $result = $conn->query($query);

  // Check if query was successful
  if ($result->num_rows > 0) {
      // Fetch all results into an array
      $users = $result->fetch_all(MYSQLI_ASSOC);
  } else {
      $users = [];
  }

  $conn->close();  // Close the connection
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Manage Users</title>
     <link rel="stylesheet" href="style.css">
  </head>
  <body>
      <h1>Manage Users</h1>
      <table>
          <tr>
              <th>User ID</th>
              <th>Username</th>
              <th>Email</th>
              <th>Full Name</th>
              <th>Role</th>
              <th>Created At</th>
              <th>Updated At</th>
              <th>Actions</th> <!-- Added column for actions -->
          </tr>
          <?php foreach ($users as $user): ?>
              <tr>
                  <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                  <td><?php echo htmlspecialchars($user['username']); ?></td>
                  <td><?php echo htmlspecialchars($user['email']); ?></td>
                  <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                  <td><?php echo htmlspecialchars($user['role']); ?></td>
                  <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                  <td><?php echo htmlspecialchars($user['updated_at']); ?></td>
                  <td>
                      <!-- Edit button -->
                      <a href="#" class="edit-btn" onclick="openModal(<?php echo $user['user_id']; ?>, '<?php echo $user['username']; ?>', '<?php echo $user['email']; ?>', '<?php echo $user['full_name']; ?>', '<?php echo $user['role']; ?>')">Edit</a>
                  </td>
              </tr>
          <?php endforeach; ?>
      </table>

      <!-- Modal -->
      <div id="editModal" class="modal">
          <div class="modal-content">
              <span class="close-btn" onclick="closeModal()">&times;</span>
              <h2>Edit User</h2>
              <form id="editForm" method="POST">
                  <input type="hidden" name="user_id" id="user_id">
                  <label>Username:</label>
                  <input type="text" name="username" id="username" required><br><br>
                  
                  <label>Email:</label>
                  <input type="email" name="email" id="email" required><br><br>

                  <label>Full Name:</label>
                  <input type="text" name="full_name" id="full_name" required><br><br>

                  <label>Role:</label>
                  <input type="text" name="role" id="role" required><br><br>

                  <button type="submit">Update User</button>
                  <button type="button" class="delete-btn" onclick="deleteUser()">Delete User</button>
                  <button type="button" onclick="closeModal()">Cancel</button>
              </form>
          </div>
      </div>

      <script>
          function openModal(userId, username, email, fullName, role) {
              document.getElementById("editModal").style.display = "block";
              document.getElementById("user_id").value = userId;
              document.getElementById("username").value = username;
              document.getElementById("email").value = email;
              document.getElementById("full_name").value = fullName;
              document.getElementById("role").value = role;
          }

          function closeModal() {
              document.getElementById("editModal").style.display = "none";
          }

          function deleteUser() {
              const userId = document.getElementById("user_id").value;
              if (confirm('Are you sure you want to delete this user?')) {
                  window.location.href = `delete_user.php?user_id=${userId}`;
              }
          }
      </script>
  </body>
  </html>
