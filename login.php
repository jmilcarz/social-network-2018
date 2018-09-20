<?php
   ### this code will work only with requried files! ###
   require('app/autoload.php');

   if (Auth::loggedin()) {
      header('Location: ' . App::$APP_DIR . 'index.php');
      exit();
   }

   if (isset($_POST['loginbtn'])) {
      $login = Security::check($_POST['login']);
      $password = Security::check($_POST['password']);
      Auth::login($login, $password);
   }

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <?php require('app/incs/head-metas.inc.php'); ?>
   <title>Login</title>
</head>
<body>
   <h1>Login</h1>
   <a href="index.php">home page</a>
   <div id="errors">
      <?php
         if (isset($_GET['error'])) {
            $error = Security::check($_GET['error']);
            if ($error == "1") {
               echo 'Incorrect password.';
            } else if ($error == "2") {
               echo 'Email doesn\'t exists!';
            } else if ($error == "3") {
               echo 'Username doesn\'t exists!';
            }
         }
      ?>
   </div>
   <form action="login.php" method="post">
      <input type="text" name="login" placeholder="username or email address">
      <input type="password" name="password" placeholder="password">
      <button type="submit" name="loginbtn">login</button>
   </form>
</body>
</html>
