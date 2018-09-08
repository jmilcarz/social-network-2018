<?php
   require('app/classes/app.php');
?>
<!DOCTYPE html>
<html lang="<?= $app->lang ?>">
<head>
   <?php require_once('app/incs/head-metas.inc.php'); ?>
   <title>Social Network</title>
</head>
<body>
   <form id="registerUserForm" method="post" action="register.php">
      <input type="text" name="firstname" placeholder="first name">
      <input type="text" name="lastname" placeholder="last name">
      <input type="email" name="email" placeholder="email">
      <input type="password" name="password" placeholder="password">
      <input type="submit" value="register" name="registerbtn">
   </form>
   <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
</body>
</html>
