<?php
   ### this code will work only with requried files! ###
   require('app/classes/app.php');
   require('app/classes/security.php');
   require('app/classes/auth.php');
   $normalize = array('Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ń'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ń'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f', 'ă'=>'a', 'î'=>'i', 'â'=>'a', 'ș'=>'s', 'ț'=>'t', 'Ă'=>'A', 'Î'=>'I', 'Â'=>'A', 'Ș'=>'S', 'Ț'=>'T', 'ą'=>'a', 'Ą'=>'A', 'ę'=>'e', 'Ę'=>'E', 'ó'=>'o', 'Ó'=>'O', 'ł'=>'l', 'Ł'=>'L', 'ć'=>'c', 'Ć'=>'C', 'ś'=>'s', 'Ś'=>'S', 'ź'=>'z', 'Ź'=>'Z','ż'=>'z', 'Ż'=>'Z');

   /* registeration is 5-steps process
      - step 1 - name, email, password, birthday, gender (płeć)
      - step 2 - username, phone (2 factor authorization), bio, avatar, background-photo
      - step 3 - language, country, city
      - step 4 - find friends (recommended friends by country and city)
      - step 5 - summary
   */

   // TODO: redisgn steps to match list above

   // TODO: then we'll make "find friends" page where user can find friends that live in the same country and city (ordered by city)
   // TODO: ? last page is the profile page with "edit view" turned on or something


   // start -- session setup
   session_start();
   // session_destroy();
   $_SESSION['step'] = 1;

   if (($_SESSION['step1_completed'] == false) && ($_SESSION['step2_completed'] == false) && ($_SESSION['step3_completed'] == false) && ($_SESSION['step4_completed'] == false)) {
      $_SESSION['step'] = 1;
   }else if (($_SESSION['step1_completed'] == true) && ($_SESSION['step2_completed'] == false) && ($_SESSION['step3_completed'] == false) && ($_SESSION['step4_completed'] == false)) {
      $_SESSION['step'] = 2;
   }else if (($_SESSION['step1_completed'] == true) && ($_SESSION['step2_completed'] == true) && ($_SESSION['step3_completed'] == false) && ($_SESSION['step4_completed'] == false)) {
      $_SESSION['step'] = 3;
   }else if (($_SESSION['step1_completed'] == true) && ($_SESSION['step2_completed'] == true) && ($_SESSION['step3_completed'] == true) && ($_SESSION['step4_completed'] == false)) {
      $_SESSION['step'] = 4;
   }else if (($_SESSION['step1_completed'] == true) && ($_SESSION['step2_completed'] == true) && ($_SESSION['step3_completed'] == true) && ($_SESSION['step4_completed'] == true)) {
      $_SESSION['step'] = 5;
   }
   // end -- session setup

   if (isset($_GET['step']) && is_numeric($_GET['step'])) {
      $step = htmlspecialchars(trim($_GET['step']));

      // start -- user cheated by trying jump over step
      if (($_SESSION['step'] == 1) && ($step != 1)) {
         header("Location: register.php?step=1");
         exit();
      }else if (($_SESSION['step'] == 2) && ($step != 2)) {
         header("Location: register.php?step=2");
         exit();
      }else if (($_SESSION['step'] == 3) && ($step != 3)) {
         header("Location: register.php?step=3");
         exit();
      }else if (($_SESSION['step'] == 4) && ($step != 4)) {
         header("Location: register.php?step=4");
         exit();
      }else if (($_SESSION['step'] == 5) && ($step != 5)) {
         header("Location: register.php?step=5");
         exit();
      }

      if ($step == 1 && $_SESSION['step'] == 1) {
         require('./app/modules/auth/register/step1.php');
         exit();
      } # $step == 1 && $_SESSION['step'] == 1

      if ($step == 2 && $_SESSION['step'] == 2) {
         require('./app/modules/auth/register/step2.php');
         exit();
      } # $step == 2 && $_SESSION['step'] == 2

      if ($step == 3 && $_SESSION['step'] == 3) {
         require('./app/modules/auth/register/step3.php');
         exit();
      } # $step == 3 && $_SESSION['step'] == 3

      if ($step == 4 && $_SESSION['step'] == 4) {
         require('./app/modules/auth/register/step4.php');
         exit();
      } # $step == 4 && $_SESSION['step'] == 4
   }else {
      header("Location: register.php?step=1");
      exit();
   }

?>
