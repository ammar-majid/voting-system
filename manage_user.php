<!DOCTYPE html>
<html>

<head>
  <title>User Management Form</title>
</head>
<?php
$operation_type = isset($_GET['id']) ? "edit" : "create";

if ($operation_type == "edit") {
  include 'db_connect.php';
  $user_id = $_GET['id'];

  // Prepare and execute the query
  $sql = "SELECT * FROM users WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  // If a user was not found
  if (!$user) {
    echo "User not found";
    exit();
  }
}
?>

<body>
  <div class="container-fluid">
    <form class="modal-form" id="manage-user">
      <input name="id" type="hidden" value="<?php echo isset($user['id']) ? $user['id'] : '' ?>" />

      <input name="type" type="hidden" id="operationType" value="<?php echo $operation_type ?>" />

      <div class="form-group">
        <label for="name">Name</label>
        <input required id="name" type="text" name="name" class="form-control" value="<?php echo isset($user['name']) ? $user['name'] : '' ?>" />
      </div>

      <div class="form-group">
        <label for="username">Username</label>
        <input required type="text" id="username" name="username" class="form-control" value="<?php echo isset($user['username']) ? $user['username'] : '' ?>" />
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input required id="password" type="password" name="password" class="form-control" value="<?php echo isset($user['password']) ? $user['password'] : '' ?>" />
      </div>

      <div class="form-group">
        <label for="type">User Type</label>

        <select name="type" id="type" class="custom-select">
          <option value="1" <?php echo isset($user['type']) && $user['type'] == 1 ? 'selected' : '' ?>>
            Admin
          </option>

          <option value="2" <?php echo isset($user['type']) && $user['type'] == 2 ? 'selected' : '' ?>>
            User
          </option>
        </select>
      </div>

      <button class="modal-submit d-none" type="button">Save</button>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      $('.modal-submit').click(function(e) {
        var username = $('#username').val(); // Get the entered username

        if ($('#operationType').val() === "edit") {
          $.ajax({
            url: 'ajax1.php?action=save_user',
            method: 'POST',
            data: $('#manage-user').serialize(),

            success: function(resp) {
              if (resp == 1) {
                alert('Data successfully saved');
                $('#uni_modal').css('display', 'none');

                setTimeout(function() {
                  location.reload();
                }, 1500);
              }
            },
          });
        }
        else {
          // Check username uniqueness using AJAX
          $.ajax({
            url: 'ajax1.php?action=check_username',
            method: 'POST',
            data: {
              username: username
            },
  
            success: function(response) {
              if (response === 'unique') {
                // If the username is unique, proceed with saving the user information
                start_load();
  
                $.ajax({
                  url: 'ajax1.php?action=save_user',
                  method: 'POST',
                  data: $('#manage-user').serialize(),
  
                  success: function(resp) {
                    if (resp == 1) {
                      alert('Data successfully saved');
                      $('#uni_modal').css('display', 'none');
  
                      setTimeout(function() {
                        location.reload();
                      }, 1500);
                    }
                  },
                });
              } 
              else {
                // If the username is not unique, display an error message
                alert('Username is not unique. Please choose a different username.');
              }
            },
            error: function(xhr, status, error) {
              console.error(error);
            },
          });
        }
      });
    });
  </script>
</body>
</html>