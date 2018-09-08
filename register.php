<?php
   require('app/classes/app.php');
   require('app/classes/security.php');
   require('app/classes/auth.php');

   /* NOTE: registeration is 5-steps process
      - step 1 - name, email, password, birthday, sex (płeć)
      - step 2 - username, phone (2 factor authorization), bio, avatar, background-photo
      - step 3 - language, country, city, religion, politics, orientation, website, etc. (I'll add later) (add to db)
      - step 4 - find friends (recommended friends)
      - step 5 - summary
   */

   // TODO: create birthday selects
   // TODO: later add school & languages, but later veeery later

   // start -- session setup
   session_start();

   $_SESSION['step'] = 1;
   // TODO: add steps later
   if (($_SESSION['step1_completed'] == true) && ($_SESSION['step2_completed'] == false)) {
      $_SESSION['step'] = 2;
   }else if (($_SESSION['step1_completed'] == true) && ($_SESSION['step2_completed'] == true)) {
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
      }else if (($_SESSION['step'] == 3) && ($step != 3)) {
         header("Location: register.php?step=3");
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
         $_SESSION['step2_completed'] = false;
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

            $_SESSION['username'] = $username;
            $_SESSION['phone'] = $phone;
            $_SESSION['bio'] = $bio;
            $_SESSION['avatar-path'] = $hashed_avatar_name;
            $_SESSION['backgroundphoto-path'] = $hashed_backgroundphoto_name;

            $_SESSION['step2_completed'] = true;
            $_SESSION['step'] = 3; # step 2 is now completed, so we update `step` variable to 3.

            header("Location: register.php?step=3");
            exit();

            // end -- validation with js & php succeeded and now data'll be saved in session's variables
         }
         // end -- register step2 validation

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
                     $usernames = DB::query('SELECT user_username FROM users');

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

      if ($step == 3 && $_SESSION['step'] == 3) { # $step is got from $_GET
         $_SESSION['step3_completed'] = false;
         // start -- registeration errors array
         $errors = [
            "",
            ""
         ];
         // end -- registeration errors array

         // start -- register step2 validation
         if (isset($_POST['registerS3'])) {
            if (empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['rpassword']) || empty($_POST['gender'])) {
               header("Location: register.php?step=1&error=1"); #1 = All fields must be filled out!
               exit();
            }

            // start -- check post variables
            $username = Security::check($_POST['username']);
            // end -- check post variables

            ## validation

            // start -- validation with js & php succeeded and now data'll be saved in session's variables

            // $_SESSION['username'] = $username;
            // $_SESSION['phone'] = $phone;
            // $_SESSION['bio'] = $bio;
            // $_SESSION['avatar-path'] = $hashed_avatar_name;
            // $_SESSION['backgroundphoto-path'] = $hashed_backgroundphoto_name;

            // $_SESSION['step3_completed'] = true;
            // $_SESSION['step'] = 4; # step 3 is now completed, so we update `step` variable to 4.

            header("Location: register.php?step=3");
            exit();

            // end -- validation with js & php succeeded and now data'll be saved in session's variables
         }
         // end -- register step2 validation
         ?>
         <!DOCTYPE html><html lang="<?= $app->lang ?>"><head><?php require_once('app/incs/head-metas.inc.php'); ?><title>Register</title></head><body><div id="registerS2"><h1>Register 3/4</h1>
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
            <form action="register.php?step=3" method="post" onsubmit="return validateRegisterS3()" name="registerS3Form">
               <?php // NOTE: language, country, city, religion, politics, orientation, website, etc. ?>
               <div>
                  <label for="language">language: </label>
                  <select name="language" id="language">
                     <option value="en">English</option>
                     <option value="es">Español</option>
                     <option value="pl">Polski</option>
                     <option value="de">Deutsch</option>
                     <option value="fr">Français</option>
                     <option value="sv">Svenska</option>
                     <option value="no">Norsk</option>
                  </select>
                  <p>If you're not a english speaker choose your native language. If your language is not available, do not worry, we will add it soon.</p>
               </div>
               <div>
                  <label for="country">country: </label>
                  <select name="country" id="country">
                     <?php
                     $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
                     foreach ($countries as $country) {
                        echo "<option value='" . strtolower($country) . "'>" . strtolower($country) . "</option>";
                     }
                     ?>
                  </select>
               </div>
               <div>
                  <label for="city">city: </label>
                  <input type="text" name="city" id="city" pattern="[a-zA-Z]+" title="City name can contain only letters!">
                  <?php // IDEA: later we'll make it rather in a different way. Our team gonna create PAGE for each indivual city on the whole world by asigning them to the `city` category! DROPDOWN, LITTLE THUMBNAIL  ?>
               </div>
               <div>
                  <label for="religion">religion: </label>
                  <input type="text" name="religion" id="religion">
                  <?php // IDEA: later we'll make it rather in a different way. Our team gonna create PAGE for each indivual religion that exists on the whole world by asigning them to the `religion` category! DROPDOWN, LITTLE THUMBNAIL  ?>
                  <p>If you wanna share with our community in what you believe, just type it. (It's not obligatory)</p>
               </div>
               <div>
                  <label for="website">website: </label>
                  <input type="text" name="website" id="website">
               </div>
               <div><button type="submit" name="registerS3" id="registerS3">continue</button></div>
            </form>
            <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
            <script src="assets/registerS3.js"></script>
         </div></body></html>
         <?php
      } # $step == 3 && $_SESSION['step'] == 3

   }else {
      header("Location: register.php?step=1");
      exit();
   }

?>
