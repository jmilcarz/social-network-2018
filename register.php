<?php
   require('app/classes/app.php');
   require('app/classes/security.php');
   require('app/classes/auth.php');

   /* registeration is 5-steps process
      - step 1 - name, email, password, birthday, sex (płeć)
      - step 2 - username, phone (2 factor authorization), bio, avatar, background-photo
      - step 3 - find friends (recommended friends)
      - step 4 - summary
   */

   // TODO: create birthday selects

   // start -- session setup
   session_start();

   $_SESSION['step'] = 1;
   if ($_SESSION['step1_completed'] == true) {
      $_SESSION['step'] = 2;
   }else if ($_SESSION['step2_completed'] == true) {
      $_SESSION['step'] = 3;
   }else if ($_SESSION['step2_completed'] == true) {
      $_SESSION['step'] = 3;
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
      }

      if ($step == 1 && $_SESSION['step'] == 1) {
         $_SESSION['step1_completed'] = false;
         // start -- registeration errors array
         $errors = [
            "All fields must be filled out!",
            "Incorrect email address!",
            "Passwords don't match!",
            "Password has to be at least 8 characters long!",
            "Repeated password has to be at least 8 characters long!",
            "Email address has to be at least 8 characters long!",
            "Incorrect gender!",
            "Incorrect first name! (min: 2 max: 32 characters)",
            "Incorrect last name! (min: 2 max: 32 characters)",
            "Name can contain only letters!"
         ];
         // end -- registeration errors array

         // start -- register step1 validation
         if (isset($_POST['registerS1']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['rpassword']) && isset($_POST['gender'])) {
            if (empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['rpassword']) || empty($_POST['gender'])) {
               header("Location: register.php?step=1&error=1"); #1 = All fields must be filled out!
               exit();
            }

            // start -- check post variables
            $firstname = Security::check($_POST['firstname']);
            $lastname = Security::check($_POST['lastname']);
            $email = Security::check($_POST['email']);
            $password = Security::check($_POST['password']);
            $rpassword = Security::check($_POST['rpassword']);
            $gender = Security::check($_POST['gender']);
            // end -- check post variables

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
               header("Location: register.php?step=1&error=2"); #2 = Incorrect email address!
               exit();
            }else if ($password != $rpassword) {
               header("Location: register.php?step=1&error=3"); #3 = Passwords don't match!
               exit();
            }else if ((strlen($password) <= 8) || (strlen($password) >= 64)) {
               header("Location: register.php?step=1&error=4"); #4 = Password has to be at least 8 characters long!
               exit();
            }else if ((strlen($rpassword) <= 8) || (strlen($rpassword) >= 64)) {
               header("Location: register.php?step=1&error=5"); #5 = Repeated password has to be at least 8 characters long!
               exit();
            }else if ((strlen($email) <= 8) || (strlen($email) >= 64)) {
               header("Location: register.php?step=1&error=6"); #6 = Email address has to be at least 8 characters long!
               exit();
            }else if ($gender != "male" && $gender != "female") {
               header("Location: register.php?step=1&error=7"); #7 = Incorrect gender!
               exit();
            }else if ((strlen($firstname) <= 2) || (strlen($firstname) >= 32)) {
               header("Location: register.php?step=1&error=8"); #8 = Incorrect first name! (min: 2 max: 32 characters)
               exit();
            }else if ((strlen($lastname) <= 2) || (strlen($lastname) >= 32)) {
               header("Location: register.php?step=1&error=9"); #9 = Incorrect last name! (min: 2 max: 32 characters)
               exit();
            }else if (!preg_match("/^[a-zA-Z]*$/", $firstname) && !preg_match("/^[a-zA-Z]*$/", $lastname)) {
               header("Location: register.php?step=1&error=10"); #10 = Name can contain only letters!
               exit();
            }

            // start -- validation with js & php succeeded and now data'll be saved in session's variables

            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
            $_SESSION['name'] = $firstname . " " . $lastname;
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password_hashed;
            $_SESSION['gender'] = $gender;
            // DB::query("INSERT INTO users VALUES (\'\', '', :fname, :lname, :name, :email, :password, '', '', '', '', '', '', '')", [':fname' => $firstname, ':lname' => $lastname, ':name' => $name, ':email' => $email, ':password' => $password_hashed]);

            $_SESSION['step1_completed'] = true;
            $_SESSION['step'] = 2; # step 1 is now completed, so we update `step` variable to 2.

            header("Location: register.php?step=2");
            exit();

            // end -- validation with js & php succeeded and now data'll be saved in session's variables
         }
         // end -- register step1 validation
         ?>
         <!DOCTYPE html><html lang="<?= $app->lang ?>"><head><?php require_once('app/incs/head-metas.inc.php'); ?><title>Register</title></head><body><div id="registerS1"><h1>Register</h1>
            <div id="errors"><?php
               if (isset($_GET['error'])) {
                  $error = htmlspecialchars(trim($_GET['error']));
                        if ($error == 1) { $error = $errors[0];
                  }else if ($error == 2) { $error = $errors[1];
                  }else if ($error == 3) { $error = $errors[2];
                  }else if ($error == 4) { $error = $errors[3];
                  }else if ($error == 5) { $error = $errors[4];
                  }else if ($error == 6) { $error = $errors[5];
                  }else if ($error == 7) { $error = $errors[6];
                  }else if ($error == 8) { $error = $errors[7];
                  }else if ($error == 9) { $error = $errors[8];
                  }else if ($error == 10) { $error = $errors[9];
                  }

                  echo $error;
               }
            ?></div>
            <form action="register.php?step=1" method="post" onsubmit="return validateRegisterS1()" name="registerS1Form">
               <div>
                  <label for="firstname">first name: </label><input type="text" name="firstname" value="" id="firstname" pattern="[a-zA-Z]+" title="First name can contain only letters!">
               </div>
               <div>
                  <label for="lastname">last name: </label><input type="text" name="lastname" value="" id="lastname" pattern="[a-zA-Z]+" title="Last name can contain only letters!">
               </div>
               <div>
                  <label for="email">email: </label><input type="text" name="email" value="" id="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" title="Incorrect email address!">
               </div>
               <div>
                  <label for="password">password: </label><input type="password" name="password" value="" id="password">
               </div>
               <div>
                  <label for="rpassword">repeat password: </label><input type="password" name="rpassword" value="" id="rpassword">
               </div>
               <div>
                  <input type="radio" name="gender" value="male" checked> Male <input type="radio" name="gender" value="female"> Female
               </div>
               <div><button type="submit" name="registerS1">register</button></div>
            </form>
            <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
            <script src="assets/registerS1.js"></script>
         </div></body></html>
         <?php
      } # $step == 1 && $_SESSION['step'] == 1

      if ($step == 2 && $_SESSION['step'] == 2) { # $step is got from $_GET
         // start -- registeration errors array
         $errors = [
            "Username field must be filled out!",
            "Incorrect username! (min: 3 max: 32 characters)",
            "Username can contain only letters & numbers!",
            "Phone number can contain only numbers!",
            "Incorrect bio! (min: 3 max: 64 characters)",
            "Problem appeared while uploading profile picture, please refresh the page.",
            "Problem appeared while uploading background picture, please refresh the page."
         ];
         // end -- registeration errors array

         // start -- register step2 validation
         if (isset($_POST['registerS2']) && isset($_POST['username'])) {
            if (empty($_POST['username'])) {
               header("Location: register.php?step=2&error=1"); #1 = Username field must be filled out!
               exit();
            }

            // start -- check post variables
            $username = Security::check($_POST['username']);
            if (empty($_POST['phone'])) {
               $phone = "phone-is-123-clear-without-any-content";
            }else {
               $phone = Security::check($_POST['phone']);
            }
            if (empty($_POST['bio'])) {
               $bio = "bio-is-123-clear-without-any-content";
            }else {
               $bio = Security::check($_POST['bio']);
            }
            if (empty($_FILES['avatar']['name'])) {
               $avatar = "avatar-is-123-clear-without-any-content";
            }else {
               $avatar = $_FILES['avatar'];
            }
            if (empty($_FILES['backgroundphoto']['name'])) {
               $backgroundphoto = "backgroundphoto-is-123-clear-without-any-content";
            }else {
               $backgroundphoto = $_FILES['backgroundphoto'];
            }

            // end -- check post variables

            if ((strlen($username) <= 3) || (strlen($username) >= 32)) {
               header("Location: register.php?step=2&error=2"); #2 = Incorrect username! (min: 3 max: 32 characters)
               exit();
            }else if (!preg_match("/[a-z0-9.]/i", $username)) {
               header("Location: register.php?step=2&error=3"); #3 = Username can contain only letters & numbers!
               exit();
            }else if ($phone != "phone-is-123-clear-without-any-content" && !preg_match("/^[0-9]*$/", $phone)) {
               header("Location: register.php?step=2&error=4"); #4 = Phone number can contain only numbers!
               exit();
            }else if ($bio != "bio-is-123-clear-without-any-content" && (strlen($bio) <= 3) && strlen($bio) >= 64) {
               header("Location: register.php?step=2&error=5"); #5 = Incorrect bio! (min: 3 max: 64 characters)
               exit();
            }

            if ($avatar != "avatar-is-123-clear-without-any-content") {
               $hashed_avatar_name = md5(basename($_FILES['avatar']['name'])) . "." . substr($_FILES['avatar']['type'], 6);
               $uploaddir = '/Applications/MAMP/htdocs/facebook/storage/pictures/⁩';
               $uploadfile = $uploaddir . $hashed_avatar_name;

               if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadfile)) {
                  echo "";
               }else {
                  header("Location: register.php?step=2&error=6"); #6 = Problem appeared while uploading profile picture, please refresh the page.
                  exit();
               }
            }

            if ($backgroundphoto != "backgroundphoto-is-123-clear-without-any-content") {
               $hashed_backgroundphoto_name = md5(basename($_FILES['backgroundphoto']['name'])) . "." . substr($_FILES['backgroundphoto']['type'], 6);
               $uploaddir = '/Applications/MAMP/htdocs/facebook/storage/pictures/⁩';
               $uploadfile = $uploaddir . $hashed_backgroundphoto_name;

               if (move_uploaded_file($_FILES['backgroundphoto']['tmp_name'], $uploadfile)) {
                  echo "";
               }else {
                  header("Location: register.php?step=2&error=7"); #6 = Problem appeared while uploading background picture, please refresh the page.
                  exit();
               }
            }

            // start -- validation with js & php succeeded and now data'll be saved in session's variables


            // end -- validation with js & php succeeded and now data'll be saved in session's variables
         }
         // end -- register step1 validation

         ?>
         <!DOCTYPE html><html lang="<?= $app->lang ?>"><head><?php require_once('app/incs/head-metas.inc.php'); ?><title>Register</title></head><body><div id="registerS2"><h1>Register 2/4</h1>
            <div id="errors"><?php
               if (isset($_GET['error'])) {
                  $error = htmlspecialchars(trim($_GET['error']));
                        if ($error == 1) { $error = $errors[0];
                  }else if ($error == 2) { $error = $errors[1];
                  }else if ($error == 3) { $error = $errors[2];
                  }else if ($error == 4) { $error = $errors[3];
                  }else if ($error == 5) { $error = $errors[4];
                  }else if ($error == 6) { $error = $errors[5];
                  }else if ($error == 7) { $error = $errors[6];
                  }

                  echo $error;
               }
            ?></div>
            <form action="register.php?step=2" method="post" onsubmit="return validateRegisterS2()" name="registerS2Form" enctype="multipart/form-data">
               <div>
                  <label for="username">username: </label>
                  <?php
                     $username = strtolower($_SESSION['firstname'] . "." . $_SESSION['lastname']);
                     $usernames = DB::query('SELECT username FROM users');

                     for ($i = 0; $i <= count($usernames); $i++) {
                        if ($username == $usernames[$i][0]) {
                           $username = $username . rand(1, 100);
                        }
                     }
                  ?>
                  <input type="text" name="username" value="<?= $username ?>" id="username">
                  <p>Add your unique username to mention others or to be mentioned!</p>
               </div>
               <hr>
               <div>
                  <label for="phone">phone number: </label>
                  <input type="tel" name="phone" value="" id="phone" phone="123456789">
                  <p>Add phone number to receive sms codes to loggin faster! (You can always add phone number later)</p>
               </div>
               <hr>
               <div>
                  <label for="bio">bio: </label>
                  <textarea name="bio" id="bio" rows="5" cols="50" value=""></textarea>
                  <p>Write something about yourself! (You can always add bio later)</p>
               </div>
               <hr>
               <div>
                  <label for="avatar">profile avatar: </label>
                  <input type="file" name="avatar" id="avatar" accept="image/*" data-type="image" onchange="return validateFile('avatar', 'avatar-preview')">
                  <div id="avatar-preview" style="width: 100px; height: 100px"></div>
                  <p>Add profile avatar to be recognized by your potential friends! (You can always add profile avatar later)</p>
               </div>
               <hr>
               <div>
                  <label for="backgroundphoto">profile's background image: </label>
                  <input type="file" name="backgroundphoto" id="backgroundphoto" accept="image/*" data-type="image" onchange="return validateFile('backgroundphoto', 'background-preview')">
                  <p>Add profile's background image to decorate your profile! (You can always add profile's background photo later)</p>
                  <div id="background-preview" style="width: 300px; height: 140px"></div>
               </div>
               <div><button type="submit" name="registerS2" id="registerS2">continue</button></div>
            </form>
            <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
            <script src="assets/registerS2.js"></script>
         </div></body></html>
         <?php
      } # $step == 2 && $_SESSION['step'] == 2

   }else {
      header("Location: register.php?step=1");
      exit();
   }

?>
