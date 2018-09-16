<?php
### this code will work only with /register.php ###

$_SESSION['step2_completed'] = false;
// start -- registeration errors array
$errors = [
   "Username field must be filled out!",
   "Incorrect username! (min: 3 max: 32 characters)",
   "Username can contain only letters & numbers!",
   "Phone number can contain only numbers!",
   "Incorrect bio! (min: 3 max: 64 characters)",
   "Problem appeared while uploading profile picture, please refresh the page.",
   "Problem appeared while uploading background picture, please refresh the page.",
   "Username already taken!"
];
// end -- registeration errors array

// start -- register step2 validation
if (isset($_POST['registerS2']) && isset($_POST['username'])) {
   if (empty($_POST['username'])) {
      header("Location: register.php?step=2&error=1"); #1 = Username field must be filled out!
      exit();
   }
   // FIXME: phone number is a string! must change that!
   // start -- check post variables
   $username = Security::check($_POST['username']);
   if (empty($_POST['phone'])) {
      $phone = "666";
   }else {
      $phone = Security::check($_POST['phone']);
   }
   if (empty($_POST['bio'])) {
      $bio = "nothing";
   }else {
      $bio = Security::check($_POST['bio']);
   }
   if (empty($_FILES['avatar']['name'])) {
      $avatar = "nothing";
   }else {
      $avatar = $_FILES['avatar'];
   }
   if (empty($_FILES['backgroundphoto']['name'])) {
      $backgroundphoto = "nothing";
   }else {
      $backgroundphoto = $_FILES['backgroundphoto'];
   }
   // end -- check post variables

   if ((strlen($username) <= 3) || (strlen($username) >= 32)) {
      header("Location: register.php?step=2&error=2"); #2 = Incorrect username! (min: 3 max: 32 characters)
      exit();
   }else if (DB::query('SELECT user_username FROM users WHERE user_username = :username', [':username' => $username])[0]['user_username']) {
      header("Location: register.php?step=2&error=8"); #8 = Username already taken!
      exit();
   }else if (!preg_match("/[a-z0-9.]/i", $username)) {
      header("Location: register.php?step=2&error=3"); #3 = Username can contain only letters & numbers!
      exit();
   }else if ($phone != "666" && !preg_match("/^[0-9]*$/", $phone)) {
      header("Location: register.php?step=2&error=4"); #4 = Phone number can contain only numbers!
      exit();
   }else if ($bio != "nothing" && (strlen($bio) <= 3) && strlen($bio) >= 64) {
      header("Location: register.php?step=2&error=5"); #5 = Incorrect bio! (min: 3 max: 64 characters)
      exit();
   }

   if ($avatar != "nothing") {
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

   if ($backgroundphoto != "nothing") {
      $hashed_backgroundphoto_name = md5(basename($_FILES['backgroundphoto']['name'])) . "." . substr($_FILES['backgroundphoto']['type'], 6);
      $uploaddir = '/Applications/MAMP/htdocs/facebook/storage/pictures/⁩';
      $uploadfile = $uploaddir . $hashed_backgroundphoto_name;

      if (move_uploaded_file($_FILES['backgroundphoto']['tmp_name'], $uploadfile)) {
         echo "";
      }else {
         header("Location: register.php?step=2&error=7"); #7 = Problem appeared while uploading background picture, please refresh the page.
         exit();
      }
   }

   // start -- validation with js & php succeeded and now data'll be saved in session's variables

   $_SESSION['username'] = $username;
   $_SESSION['phone'] = $phone;
   $_SESSION['bio'] = $bio;
   if ($avatar == "nothing") {
      $_SESSION['avatar'] = "no-photo";
   }else {
      $_SESSION['avatar'] = $hashed_avatar_name;
   }
   if ($backgroundphoto == "nothing") {
      $_SESSION['backgroundphoto'] = "no-photo";
   }else {
      $_SESSION['backgroundphoto'] = $hashed_backgroundphoto_name;
   }

   if ($_SESSION['phone'] == "666") {
      $_SESSION['phone'] = 0;
   }

   $_SESSION['step1_completed'] = true;
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
   <form action="register.php?step=2" method="post" onsubmit="return validateRegisterS2()" name="registerS2Form" enctype="multipart/form-data">
      <div>
         <label for="username">username: </label>
         <?php

            $username = strtolower(strtr($_SESSION['firstname'], $normalize) . "." . strtr($_SESSION['lastname'], $normalize));
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
