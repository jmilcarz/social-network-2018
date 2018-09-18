<?php
require_once('app/autoload.php');
if (Auth::loggedin()) {
   header("Location: index.php");
   exit();
}
if (isset($_POST['send'])) {
   $email = Security::check($_POST['email']);
   Auth::forgotPassword($email);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require('app/incs/head-metas.inc.php'); ?>
     <title>forgot password</title>
</head>
<body>
   <?= Auth::$error; ?>
   <?php
      if (isset($_GET['error'])) {
         $error = Security::check($_GET['error']);
         if ($error == "1") {
            echo 'Check your mailbox.';
         } else if ($error == "2") {
            echo 'Email doesn\'t exists!';
         } else if ($error == "3") {
            echo 'Invalid email address!';
         } else if ($error == "4") {
            echo 'Email must contain \'@\' character!';
         }
      }
   ?>
   <form action="forgot-password.php" method="post">
      <input type="email" name="email" placeholder="E-mail address">
      <input type="submit" name="send" value="send email">
   </form>
</body>
</html>
