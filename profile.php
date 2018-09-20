<?php
require('app/autoload.php');

if (isset($_GET['u'])) {
   $gotuser = Security::check($_GET['u']);
   if (is_numeric($gotuser)) {
      if (DB::query('SELECT id FROM users WHERE id = :userid', [':userid' => $gotuser])[0]['id']) {
         $profileUser = DB::query('SELECT * FROM users, user_info WHERE users.id = :userid AND useri_user_id = :userid', [':userid' => $gotuser])[0];
      }else {
         # user doesn't exists!
         echo 'you shall not pass!';
         exit();
      }

   } else {
      if (DB::query('SELECT user_username FROM users WHERE user_username = :username', [':username' => $gotuser])[0]['user_username']) {
         $gotUserId = DB::query('SELECT id FROM users WHERE user_username = :username', [':username' => $gotuser])[0]['id'];
         $profileUser = DB::query('SELECT * FROM users, user_info WHERE users.id = :userid AND useri_user_id = :userid', [':userid' => $gotUserId])[0];
      } else {
         # user doesn't exists!
         echo 'you shall not pass!';
         exit();
      }

   }

   ?>

   <!DOCTYPE html>
   <html lang="en">
   <head>
      <?php require('app/incs/head-metas.inc.php'); ?>
      <title><?= $profileUser['user_name']; ?></title>
      <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
      <?php if (Auth::loggedin()) { ?>
         <script>
            $(function() {
               $.post( "http://localhost/facebook/app/api/friends.php", { APIload: true, APIUserId: <?php echo $profileUser['id']; ?>, APILoggedinId: <?php echo Auth::loggedin(); ?> })
               .done(function( data ) {
                  $('#friendActionsCTAS').html(data);

               });
            });
            setInterval(function() {
               $.ajax({
                  url: "http://localhost/facebook/app/api/friends.php",
                  type: 'POST',
                  data: { APIload: true, APIUserId: <?php echo $profileUser['id']; ?>, APILoggedinId: <?php echo Auth::loggedin(); ?> },
                  success: function(data) {
                     $('#friendActionsCTAS').html(data);
                  }
               })
            }, 15000);

         <?php if (DB::query('SELECT friends_id FROM friends WHERE (friends_userid = :userid AND friends_friendid = :friendid) OR (friends_userid = :friendid AND friends_friendid = :userid)', [':userid' => Auth::loggedin(), ':friendid' => $profileUser['id']])[0]['friends_id']) { ?>
            $(function() {
               $.post( "http://localhost/facebook/app/api/follow.php", { APIload: true, APIUserId: <?php echo $profileUser['id']; ?>, APILoggedinId: <?php echo Auth::loggedin(); ?> })
               .done(function( data ) {
                  $('#friendFollowingsActionsCTAS').html(data);
               });
            });
         </script>
      <?php } else { echo '</script>'; } ?>

      <?php } ?>
   </head>
   <body>
      <?php
      echo 'loggedin: ' . Auth::loggedin() . " | profileUser: " . $profileUser['id'];
      ?>
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
      <h1><?= $profileUser['user_name']; ?> (<?= $profileUser['id']; ?>)</h1>
      <h2>username: @<?= $profileUser['user_username']; ?></h2>
      <p>bio: <?= $profileUser['useri_bio']; ?></p>
      <p>gender: <?= $profileUser['useri_gender']; ?></p>
      <hr>
      <?php if (Auth::loggedin() && $profileUser['id'] != Auth::loggedin()) { # loading from AJAX ?>
         <div class="cta" id="friendActionsCTAS"></div>
      <?php } ?>
   </body>
   </html>

<?php } else { header("Location: http://localhost/facebook/index.php"); exit(); } ?>
