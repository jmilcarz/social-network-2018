<?php
### this code will work only with /register.php ###

$_SESSION['step4_completed'] = false;

// start -- registeration errors array
$errors = [
   "All fields except religion & website must be filled out!",
   "Incorrect language!",
   "Incorrect city!",
   "City name has to be at least 3 characters long!",
   "Incorrect religion!",
   "Religion has to be at least 3 characters long!",
   "Incorrect country!"
];
// end -- registeration errors array


?>
<!DOCTYPE html><html lang="<?= $app->lang ?>"><head><?php require_once('app/incs/head-metas.inc.php'); ?><title>Register</title></head><body><div id="registerS4"><h1>Register 4/4</h1>
   <div id="errors"><?php
      if (isset($_GET['error'])) {
         $error = htmlspecialchars(trim($_GET['error']));
              if ($error == 1)  { $error = $errors[0];  }
         else if ($error == 2)  { $error = $errors[1];  }
         else if ($error == 3)  { $error = $errors[2];  }
         else if ($error == 4)  { $error = $errors[3];  }
         else if ($error == 5)  { $error = $errors[4];  }
         else if ($error == 6)  { $error = $errors[5];  }
         else if ($error == 7)  { $error = $errors[6];  }
         else if ($error == 8)  { $error = $errors[7];  }
         else if ($error == 9)  { $error = $errors[8];  }
         else if ($error == 10) { $error = $errors[9];  }
         else if ($error == 11) { $error = $errors[10]; }

         echo $error;
      }
   ?></div>
   <h2>People that you can know.</h2>
   <div id="recommendBox"></div>


   <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
   <script src="assets/registerS4.js"></script>
</div></body></html>
