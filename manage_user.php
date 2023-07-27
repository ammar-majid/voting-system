<!DOCTYPE html>
<html>
<head>
  <title>User Management Form</title>
</head>
<body>
  <div class="container-fluid">
    <form action="" id="manage-user">
      <input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id'] : '' ?>">
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" class="form-control" value="<?php echo isset($meta['name']) ? $meta['name'] : '' ?>" required>
      </div>
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username'] : '' ?>" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control" value="<?php echo isset($meta['password']) ? $meta['password'] : '' ?>" required>
      </div>
      <div class="form-group">
        <label for="type">User Type</label>
        <select name="type" id="type" class="custom-select">
          <option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected' : '' ?>>Admin</option>
          <option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected' : '' ?>>User</option>
        </select>
      </div>
      <button type="submit">Save</button>
    </form>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#manage-user').submit(function (e) {
        e.preventDefault();
        var username = $('#username').val(); // Get the entered username

        // Check username uniqueness using AJAX
        $.ajax({
          url: 'ajax1.php?action=check_username',
          method: 'POST',
          data: { username: username },
          success: function (response) {
            if (response === 'unique') {
              // If the username is unique, proceed with saving the user information
              start_load();
              $.ajax({
                url: 'ajax1.php?action=save_user',
                method: 'POST',
                data: $('#manage-user').serialize(),
                success: function (resp) {
                  if (resp == 1) {
                    alert('Data successfully saved');
                    setTimeout(function () {
                      location.reload();
                    }, 1500);
                  }
                },
              });
            } else {
              // If the username is not unique, display an error message
              alert('Username is not unique. Please choose a different username.');
            }
          },
          error: function (xhr, status, error) {
            console.error(error);
          },
        });
      });
    });
  </script>
</body>
</html>
