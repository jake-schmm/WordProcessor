<?php
$servername = "localhost";
$user = "root";
$pass= "";
$db = "wordprocessordb";
$error_messages = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if (empty($username)) {
        $error_messages[] = "Username required.";
    }
    if (empty($password)) {
        $error_messages[] = "Password required.";
    }
    if ($password != $password_confirm) {
        $error_messages[] = "Passwords must match.";
    }

    $conn = new mysqli($servername, $user, $pass, $db);
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0) {
        $error_messages[] = 'User with username already exists';
    }
    else {
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        header('Location: login.php');
    }
    $stmt->close();
    $conn->close();
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <style>
      .form-container {
        width: 400px;
        margin: 0 auto;
        padding: 30px;
        background-color: #f7f7f7;
        border-radius: 10px;
        box-shadow: 0 0 10px #ccc;
      }
      h3 {
        text-align: center;
        margin-bottom: 30px;
      }
    </style>
  </head>
  <body>
    <div class="form-container">
      <h3>Create Your Account</h3>
      <?php if ($error_messages): ?>
        <?php foreach ($error_messages as $error_message): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endforeach; ?>
      <?php endif; ?>
      <form action="register.php" method="post">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" class="form-control" id="username" name="username" required>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-group">
          <label for="password_confirm">Confirm Password</label>
          <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
        </div>
        <hr>
        <button type="submit" class="btn btn-success btn-block">Register</button>
        <div class="text-center mt-3">
        Already have an account? <a href="login.php">Login</a>
        </div>
        <hr>
        </form>
    </div>
  </body>
</html>


