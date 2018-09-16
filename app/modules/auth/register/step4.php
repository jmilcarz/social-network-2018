<?php
### this code will work only with /register.php ###

$_SESSION['step4_completed'] = false;

if (isset($_POST['procceedtostep5'])) {

   session_destroy();
   Auth::login($_SESSION['email'], $_SESSION['passwordnh']);
}

?>
<!DOCTYPE html><html lang="<?= $app->lang ?>"><head><?php require_once('app/incs/head-metas.inc.php'); ?><title>Register</title></head><body><div id="registerS4"><h1>Register 4/4</h1>
   <h2>People that you can know.</h2>
   <div id="recommendBox"></div>

   <form action="register.php?step=4" method="post">
      <button type="submit" name="procceedtostep5">countinue to summary</button>
   </form>

   <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
   <script src="assets/registerS4.js"></script>
</div></body></html>
