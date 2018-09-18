<?php
require('app/autoload.php');

if (isset($_GET['u'])) {
   $gotuser = Security::check($_GET['u']);
   if (is_numeric($gotuser)) {
      if (DB::query('SELECT id FROM users WHERE id = :userid', [':userid' => $gotuser])[0]['id']) {
         $profileUser = DB::query('SELECT * FROM users, user_info WHERE users.id = :userid AND useri_user_id = :userid', [':userid' => $gotuser])[0];
         // echo '<pre>';
         // print_r($profileUser);
         // echo '</pre>';
      }else {
         # user doesn't exists!
         echo 'you shall not pass!';
         exit();
      }

   } else {
      if (DB::query('SELECT user_username FROM users WHERE user_username = :username', [':username' => $gotuser])[0]['user_username']) {
         $gotUserId = DB::query('SELECT id FROM users WHERE user_username = :username', [':username' => $gotuser])[0]['id'];
         $profileUser = DB::query('SELECT * FROM users, user_info WHERE users.id = :userid AND useri_user_id = :userid', [':userid' => $gotUserId])[0];
         // echo '<pre>';
         // print_r($profileUser);
         // echo '</pre>';
      } else {
         # user doesn't exists!
         echo 'you shall not pass!';
         exit();
      }

   }
} else {
   echo 'you shall not pass!';
   exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <?php require('app/incs/head-metas.inc.php'); ?>
   <title><?= $profileUser['user_name']; ?></title>
</head>
<body>
   <img src="<?php
   if ($profileUser['user_avatar'] == 'no-photo') {
      echo 'https://www.ischool.berkeley.edu/sites/default/files/default_images/avatar.jpeg';
   } else {
      echo 'http://localhost/facebook/storage/pictures/%E2%81%A9' . $profileUser['user_avatar'];
   }

   ?>" width="150" height="150">
   <img src="<?php
   if ($profileUser['user_backgroundphoto'] == 'no-photo') {
      echo 'https://i.imgur.com/LTtOyNfg.png';
   } else {
      echo 'http://localhost/facebook/storage/pictures/%E2%81%A9' . $profileUser['user_backgroundphoto'];
   }

   ?>" height="150">
   <h1><?= $profileUser['user_name']; ?></h1>
   <h2>username: @<?= $profileUser['user_username']; ?></h2>
   <p>bio: <?= $profileUser['useri_bio']; ?></p>
   <p>gender: <?= $profileUser['useri_gender']; ?></p>
   <hr>
   <?php
   if (Auth::loggedin()) {
      ?>
      <div class="cta" id= "ctaBoxId<?php echo $userid; ?>">
         <?php
            if (DB::query('SELECT friendr_id FROM friend_requests WHERE (friendr_senderid = :senderid AND friendr_receiverid = :receiverid) OR (friendr_senderid = :receiverid AND friendr_receiverid = :senderid)', [':senderid' => $loggedinuser, ':receiverid' => $userid])[0]['friendr_id']) {
               ?>
               <form id="form<?php echo $userid; ?>">
                  <button type="submit" id="cancel<?php echo $userid; ?>">cancel friend request</button>
               </form>
               <script>
                  $('#form<?php echo $userid; ?>').on('submit', function(e) {
                     e.preventDefault();
                     let userid = <?php echo $userid; ?>;
                     let loggedUserid = <?php echo $loggedinuser; ?>;
                     $.post( "app/api/auth/friendRequestHandler.php", { CANCELuserid: userid, CANCELloggedUserid: loggedUserid })
                     .done(function( data ) {
                        $('#ctaBoxId<?php echo $userid; ?>').html(data);
                     });
                  });
               </script>
               <?php
            } else {
            ?>
            <form id="form<?php echo $userid; ?>">
               <button type="submit" id="send<?php echo $userid; ?>">add to friends</button>
            </form>
            <script>
               $('#form<?php echo $userid; ?>').on('submit', function(e) {
                  e.preventDefault();
                  let userid = <?php echo $userid; ?>;
                  let loggedUserid = <?php echo $loggedinuser; ?>;
                  $.post( "../app/api/auth/friendRequestHandler.php", { SENDuserid: userid, SENDloggedUserid: loggedUserid })
                  .done(function( data ) {
                     $('#ctaBoxId<?php echo $userid; ?>').html(data);
                  });
               });
            </script>
      <?php } ?>
      </div>

      <?php
   }
   ?>
   <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
</body>
</html>
