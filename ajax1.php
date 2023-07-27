<?php
if ($_GET['action'] === 'check_username') {
  // This block handles the username uniqueness check
  include('db_connect.php');
  $username = $_POST['username'];

  // Query the database to check if the username already exists
  $result = $conn->query("SELECT COUNT(*) as count FROM users WHERE username = '$username'");
  $row = $result->fetch_assoc();
  $count = $row['count'];

  // Respond based on the uniqueness of the username
  if ($count == 0) {
    echo 'unique';
  } else {
    echo 'not_unique';
  }
  exit; // Stop further processing of ajax.php after the check
}

// The following block handles saving/updating user information
if ($_GET['action'] === 'save_user') {
  include('db_connect.php');
  
  // Get the form data
  $id = $_POST['id'];
  $name = $_POST['name'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $type = $_POST['type'];

  // Check if the ID is set (existing user) or not (new user)
  if (!empty($id)) {
    // Existing user, update the information
    $update_query = "UPDATE users SET name = '$name', username = '$username', password = '$password', type = '$type' WHERE id = '$id'";
    if ($conn->query($update_query) === TRUE) {
      echo 1; // Success
    } else {
      echo 0; // Error
    }
  } else {
    // New user, insert the information
    // Check if the username is unique before inserting
    $result = $conn->query("SELECT COUNT(*) as count FROM users WHERE username = '$username'");
    $row = $result->fetch_assoc();
    $count = $row['count'];
    if ($count == 0) {
      // Username is unique, insert the data
      $insert_query = "INSERT INTO users (name, username, password, type) VALUES ('$name', '$username', '$password', '$type')";
      if ($conn->query($insert_query) === TRUE) {
        echo 1; // Success
      } else {
        echo 0; // Error
      }
    } else {
      echo -1; // Username not unique
    }
  }
  exit;
}
?>
