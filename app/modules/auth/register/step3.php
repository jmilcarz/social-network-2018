<?php
### this code will work only with /register.php ###

$_SESSION['step3_completed'] = false;
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

// start -- register step3 validation
if (isset($_POST['registerS3'])) {
   if (empty($_POST['language']) || empty($_POST['country']) || empty($_POST['city'])) {
      header("Location: register.php?step=3&error=1"); #1 = All fields except religion & website must be filled out!
      exit();
   }

   // start -- check post variables
   $language = Security::check($_POST['language']);
   $country = Security::check($_POST['country']);
   $city = Security::check($_POST['city']);
   if (empty($_POST['religion'])) {
      $religion = "nothing";
   }else {
      $religion = Security::check($_POST['city']);
   }
   // end -- check post variables

   if ($language == "en" || $language == "es" || $language == "pl" || $language == "de" || $language == "fr") {
      echo " ";
   }else {
      header("Location: register.php?step=3&error=2"); #2 = Incorrect language!
      exit();
   }

   $countries = ["Afghanistan", "Aland Islands", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Barbuda", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Trty.", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Caicos Islands", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Futuna Islands", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard", "Herzegovina", "Holy See", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Jan Mayen Islands", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea", "Korea (Democratic)", "Kuwait", "Kyrgyzstan", "Lao", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "McDonald Islands", "Mexico", "Micronesia", "Miquelon", "Moldova", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "Nevis", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestinian Territory, Occupied", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Principe", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Barthelemy", "Saint Helena", "Saint Kitts", "Saint Lucia", "Saint Martin (French part)", "Saint Pierre", "Saint Vincent", "Samoa", "San Marino", "Sao Tome", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia", "South Sandwich Islands", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "The Grenadines", "Timor-Leste", "Tobago", "Togo", "Tokelau", "Tonga", "Trinidad", "Tunisia", "Turkey", "Turkmenistan", "Turks Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "US Minor Outlying Islands", "Uzbekistan", "Vanuatu", "Vatican City State", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (US)", "Wallis", "Western Sahara", "Yemen", "Zambia", "Zimbabwe"];

   if (!in_array(ucfirst($country), $countries)) {
      header("Location: register.php?step=3&error=7"); #3 = Incorrect country!
      exit();
   }else if (!preg_match("/[a-zA-Zs]/i", $city)) {
      header("Location: register.php?step=3&error=3"); #7 = Incorrect city!
      exit();
   }else if ((strlen($city) <= 3) && (strlen($city) >= 48)) {
      header("Location: register.php?step=3&error=4"); #4 = City name has to be at least 3 characters long!
      exit();
   }
   if ($religion != "") {
      if (!preg_match("/[a-zA-Zs]/i", $religion)) {
         header("Location: register.php?step=3&error=5"); #5 = Incorrect religion!
         exit();
      }else if ((strlen($religion) <= 3) && (strlen($religion) >= 48)) {
         header("Location: register.php?step=3&error=6"); #6 = Religion has to be at least 3 characters long!
         exit();
      }
   }

   // start -- validation with js & php succeeded and now data'll be saved in session's variables

   $_SESSION['language'] = $language;
   $_SESSION['country'] = $country;
   $_SESSION['city'] = $city;
   $_SESSION['religion'] = $religion;

   if ($_SESSION['phone'] == "666") {
      $_SESSION['phone'] = 0;
   }

   DB::query('INSERT INTO users VALUES (\'\', :username, :firstname, :lastname, :name, :email, :phone, :password, :birthday, :sex, :avatar, :backgroundphoto)', [':username' => $_SESSION['username'], ':firstname' => $_SESSION['firstname'], ':lastname' => $_SESSION['lastname'], ':name' => $_SESSION['name'], ':email' => $_SESSION['email'], ':phone' => $_SESSION['phone'], ':password' => $_SESSION['password'], ':birthday' => $_SESSION['birthday'], ':sex' => $_SESSION['sex'], ':avatar' => $_SESSION['avatar'], ':backgroundphoto' => $_SESSION['backgroundphoto']]);
   echo "works!";
   $userid = DB::query('SELECT id FROM users WHERE user_username = :username AND user_email = :email AND user_password = :password', [':username' => $_SESSION['username'], ':email' => $_SESSION['email'], ':password' => $_SESSION['password']])[0]['id'];
   DB::query('INSERT INTO user_info VALUES (\'\', :userid, :lang, :country, :city, :bio, :gender, :religion)', [':userid' => $userid, ':lang' => $_SESSION['language'], ':country' => $_SESSION['country'], ':city' => $_SESSION['city'], ':bio' => $_SESSION['bio'], ':gender' => $_SESSION['gender'], ':religion' => $_SESSION['religion']]);

   $_SESSION['step1_completed'] = true;
   $_SESSION['step2_completed'] = true;
   $_SESSION['step3_completed'] = true;
   $_SESSION['step'] = 4; # step 3 is now completed, so we update `step` variable to 4.

   header("Location: register.php?step=4");
   exit();

   // end -- validation with js & php succeeded and now data'll be saved in session's variables
}
// end -- register step3 validation
?>
<!DOCTYPE html><html lang="<?= $app->lang ?>"><head><?php require_once('app/incs/head-metas.inc.php'); ?><title>Register</title></head><body><div id="registerS3"><h1>Register 3/4</h1>
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
         </select>
         <p>If you're not a english speaker choose your native language. If your language is not available, do not worry, we will add it soon.</p>
      </div>
      <div>
         <label for="country">country: </label>
         <select name="country" id="country">
            <?php
            $countries = array("Afghanistan", "Aland Islands", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Barbuda", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Trty.", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Caicos Islands", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Futuna Islands", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard", "Herzegovina", "Holy See", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Jan Mayen Islands", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea", "Korea (Democratic)", "Kuwait", "Kyrgyzstan", "Lao", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "McDonald Islands", "Mexico", "Micronesia", "Miquelon", "Moldova", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "Nevis", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestinian Territory, Occupied", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Principe", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Barthelemy", "Saint Helena", "Saint Kitts", "Saint Lucia", "Saint Martin (French part)", "Saint Pierre", "Saint Vincent", "Samoa", "San Marino", "Sao Tome", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia", "South Sandwich Islands", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "The Grenadines", "Timor-Leste", "Tobago", "Togo", "Tokelau", "Tonga", "Trinidad", "Tunisia", "Turkey", "Turkmenistan", "Turks Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "US Minor Outlying Islands", "Uzbekistan", "Vanuatu", "Vatican City State", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (US)", "Wallis", "Western Sahara", "Yemen", "Zambia", "Zimbabwe");
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
      <div><button type="submit" name="registerS3" id="registerS3">continue</button></div>
   </form>
   <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
   <script src="assets/registerS3.js"></script>
</div></body></html>
