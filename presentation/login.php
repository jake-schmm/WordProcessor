<?php
require_once('../bootstrap.php');
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
  
    $result = $userManager->authenticateUser($username, $password);
    if($result->status === "success") {
        session_start();
        $_SESSION["userID"] = $result->message; // message will contain userID of authenticated user if status is success
        $_SESSION["username"] = $username;

        header('Location: index.php');
        exit();
    }
    else {
        $error_message = $result->message;
    }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
      body {
            background-image: url('../images/background5.png');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
  </head>
  <body>
    <div class="form-container">
      <h3>Word Processor Login</h3>
      <?php if ($error_message): ?>
        <div class="alert alert-danger" role="alert">
          <?php echo $error_message; ?>
        </div>
      <?php endif; ?>
      <form action="login.php" method="post">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-success btn-block">Login</button>
      </form>
      <div class="text-center mt-3">
        Don't have an account? <a href="register.php">Register</a>
      </div>
    </div>
  </body>
</html>
