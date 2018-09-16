<?php require('app/autoload.php');

if (!Auth::loggedin()) {
   echo 'ggg';
}else {
   // TODO: timeline
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <?php require('app/incs/head-metas.inc.php'); ?>
   <title>Social Network</title>
</head>
<body>
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
