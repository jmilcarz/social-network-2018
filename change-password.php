<?php
require_once('app/autoload.php');
$tokenIsValid = false;
if (Auth::loggedin()) {
   if (isset($_POST['send'])) {
      $oldpass = Security::check($_POST['opass']);
      $newpass = Security::check($_POST['npass']);
      $rnewpass = Security::check($_POST['rnpass']);
      Auth::changePassword($oldpass, $newpass, $rnewpass);
   }
}else {
   if (isset($_GET['token'])) {
      $token = Security::check($_GET['token']);
      if (DB::query('SELECT passwordt_userid FROM password_tokens WHERE passwordt_token=:token', array(':token'=>sha1($token)))) {
         $userid = DB::query('SELECT passwordt_userid FROM password_tokens WHERE passwordt_token=:token', array(':token'=>sha1($token)))[0]['passwordt_userid'];
         $tokenIsValid = True;
         if (isset($_POST['send'])) {
            $newpass = Security::check($_POST['npass']);
            $rnewpass = Security::check($_POST['rnpass']);
            Auth::changePasswordToken($newpass, $rnewpass, $userid);
         }
      }
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require('app/incs/head-metas.inc.php'); ?>
     <title>change password</title>
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
     <form action="<?php if (!$tokenIsValid) { echo 'change-password.php'; } else { echo 'change-password.php?token='.$token.''; } ?>" method="post">
          <?php if (!$tokenIsValid) { echo '<input type="password" name="opass" placeholder="Old Password">'; } ?>
          <input type="password" name="npass" placeholder="New Password">
          <input type="password" name="rnpass" placeholder="New Repeat Password">
          <input type="submit" name="send" value="change password">
     </form>
</body>
</html>
