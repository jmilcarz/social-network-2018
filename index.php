<?php require('app/autoload.php');

if (!Auth::loggedin()) {
   ?>

   <!DOCTYPE html>
   <html lang="en">
   <head>
      <?php require('app/incs/head-metas.inc.php'); ?>
      <title>Social Network</title>
   </head>
   <body>
      <h1>Home Page</h1>
      <a href="register.php">register</a><br>
      <div id="errors">
         <?php
         if (isset($_GET['error'])) {
            $error = Security::check($_GET['error']);

            echo $error;
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

   <?php
   exit(); }
?>

<form action="" method="post">
   <button name="logoutbtn" type="submit">logout</button>
</form>
