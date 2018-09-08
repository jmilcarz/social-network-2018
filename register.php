<?php
   require('app/classes/app.php');
   require('app/classes/security.php');
   require('app/classes/auth.php');

   if (isset($_POST['registerbtn'])) {
      $first_name = $_POST['firstname'];
      $last_name = $_POST['lastname'];
      $email = $_POST['email'];
      $password = $_POST['password'];

      if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
         header("Location: register.php?firstname=" . $first_name . "&lastname=" . $last_name . "&email=" . $email . "&error=e1");
         exit();
      }

      $first_name = Security::check($_POST['firstname']);
      $last_name = Security::check($_POST['lastname']);
      $email = Security::check($_POST['email']);
      $password = Security::check($_POST['password']);

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         $big_error = ""; $error_firstname = ""; $error_lastname = ""; $error_email = "Niepoprawny Adres Email!"; $error_password = "";
         header("Location: register.php?firstname=" . $first_name . "&lastname=" . $last_name . "&email=" . $email);
         exit();
      }else if (strlen($first_name) <= 2 || strlen($first_name) >= 22) {
         $big_error = ""; $error_firstname = "Niepoprawna długość imienia! (min: 2 max: 22)"; $error_lastname = ""; $error_email = ""; $error_password = "";
         header("Location: register.php?firstname=" . $first_name . "&lastname=" . $last_name . "&email=" . $email);
         exit();
      }else if (strlen($last_name) <= 2 || strlen($last_name) >= 22) {
         $big_error = ""; $error_firstname = ""; $error_lastname = "Niepoprawna długość nazwiska! (min: 2 max: 22)"; $error_email = ""; $error_password = "";
         header("Location: register.php?firstname=" . $first_name . "&lastname=" . $last_name . "&email=" . $email);
         exit();
      }else if (!preg_match("/^[a-zA-Z]+$/", $first_name)) {
         $big_error = ""; $error_firstname = "Niepoprawne imię!"; $error_lastname = ""; $error_email = ""; $error_password = "";
         header("Location: register.php?firstname=" . $first_name . "&lastname=" . $last_name . "&email=" . $email);
         exit();
      }else if (!preg_match("/^[a-zA-Z]+$/", $last_name)) {
         $big_error = ""; $error_firstname = ""; $error_lastname = "Niepoprawne nazwisko!"; $error_email = ""; $error_password = "";
         header("Location: register.php?firstname=" . $first_name . "&lastname=" . $last_name . "&email=" . $email);
         exit();
      }
   }

?>
<!DOCTYPE html>
<html lang="<?= $app->lang ?>">
<head>
   <?php require_once('app/incs/head-metas.inc.php'); ?>
   <title>Register</title>
</head>
<body>
   <h1>Register</h1>
   <?php
      if (isset($_GET['error'])) {
         $error = Security::check($GET['error']);
         echo $error;
      }
   ?>
   <form id="registerUserForm" method="post" action="register.php">
      <?php
      if (isset($_GET['firstname'])) {
         $Gfirstname = Security::check($_GET['firstname']);
         echo '<input type="text" name="firstname" placeholder="first name" value="' . $Gfirstname . '">';
         echo '<span>' . $error_firstname . '</span>';
      }else {
         echo '<input type="text" name="firstname" placeholder="first name">';
         echo '<span>' . $error_firstname . '</span>';
      }

      if (isset($_GET['lastname'])) {
         $Glastname = Security::check($_GET['lastname']);
         echo '<input type="text" name="lastname" placeholder="last name" value="' . $Glastname . '">';
         echo '<span>' . $error_lastname . '</span>';
      }else {
         echo '<input type="text" name="lastname" placeholder="last name">';
         echo '<span>' . $error_lastname . '</span>';
      }

      if (isset($_GET['email'])) {
         $Gemail = Security::check($_GET['email']);
         echo '<input type="email" name="email" placeholder="email" value="' . $Gemail . '">';
         echo '<span>' . $error_email . '</span>';
      }else {
         echo '<input type="email" name="email" placeholder="email">';
         echo '<span>' . $error_email . '</span>';
      }
      ?>
      <input type="password" name="password" placeholder="password">
      <span><?= $error_password ?></span>
      <input type="submit" value="register" name="registerbtn">
   </form>
   <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
</body>
</html>
