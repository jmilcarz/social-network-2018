<?php
   require('app/classes/app.php');
   require('app/classes/security.php');
   require('app/classes/auth.php');

   /* registeration is 5-steps process
      - step 1 - name, email, password, sex (płeć)
      - step 2 - username, phone (2 factor authorization), said_sex (jak się zwracać (on lub ona)), bio (info about user)
      - step 3 - avatar, background-photo
      - step 4 - find friends (recommended friends)
      - step 5 - summary
   */

?>
<!DOCTYPE html>
<html lang="<?= $app->lang ?>">
<head>
   <?php require_once('app/incs/head-metas.inc.php'); ?>
   <title>Register</title>
</head>
<body>
   <h1>Register</h1>
   <div id="errors"></div>
   <form action="register.php?step=1" method="post" onsubmit="return validateRegisterS1()" name="registerS1Form">
      <div>
         <label for="firstname">first name: </label>
         <input type="text" name="firstname" value="" id="firstname" pattern="[a-zA-Z]+" title="First name can contain only letters!">
      </div>
      <div>
         <label for="lastname">last name: </label>
         <input type="text" name="lastname" value="" id="lastname" pattern="[a-zA-Z]+" title="Last name can contain only letters!">
      </div>
      <div>
         <label for="email">email: </label>
         <input type="text" name="email" value="" id="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" title="Incorrect Email Adress!">
      </div>
      <div>
         <label for="password">password: </label>
         <input type="text" name="password" value="" id="passowrd">
      </div>
      <div>
         <label for="rpassword">repeat password: </label>
         <input type="text" name="rpassword" value="" id="rpassword">
      </div>
      <div>
         <input type="radio" name="gender" value="male" checked> Male
         <input type="radio" name="gender" value="female"> Female
      </div>
      <div><button type="submit" name="registerS1">register</button></div>
   </form>
   <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
   <script src="assets/registerS1.js"></script>
</body>
</html>