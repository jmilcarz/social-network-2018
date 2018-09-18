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

   if (Auth::loggedin()) {
      if (isset($_POST['sendFR'])) {
         $message = $_POST['sendFRMessage'];
         Friends::sendFriendRequest(Auth::loggedin(), $profileUser['id'], $message);
      }

      if (isset($_POST['cancelFR'])) {
         $requestid = DB::query('SELECT friendr_id FROM friend_requests WHERE (friendr_senderid = :senderid AND friendr_receiverid = :receiverid) OR (friendr_senderid = :receiverid AND friendr_receiverid = :senderid)', [':senderid' => $profileUser['id'], ':receiverid' => Auth::loggedin()])[0]['friendr_id'];
         Friends::cancelFriendRequest($requestid, Auth::loggedin(), $profileUser['id']);
      }

      if (isset($_POST['acceptFR'])) {
         $requestid = DB::query('SELECT friendr_id FROM friend_requests WHERE (friendr_senderid = :senderid AND friendr_receiverid = :receiverid) OR (friendr_senderid = :receiverid AND friendr_receiverid = :senderid)', [':senderid' => $profileUser['id'], ':receiverid' => Auth::loggedin()])[0]['friendr_id'];
         Friends::acceptFriendRequest($requestid, $profileUser['id'], Auth::loggedin());
      }

      if (isset($_POST['cancelFR'])) {
         Friends::cancelFriendRequest($requestid, Auth::loggedin(), $profileUser['id']);
      }

      if (isset($_POST['deleteFR'])) {
         Friends::deleteFriend(Auth::loggedin(), $profileUser['id']);
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
   <?php if (Auth::loggedin() && $profileUser['id'] != Auth::loggedin()) { ?>
   <div class="cta" id= "ctaBoxId">
      <?php if (DB::query('SELECT friends_id FROM friends WHERE (friends_userid = :userid AND friends_friendid = :friendid) OR (friends_userid = :friendid AND friends_friendid = :userid)', [':userid' => Auth::loggedin(), ':friendid' => $profileUser['id']])) { ?>
         <form method="post" action="">
            <button type="submit" name="deleteFR">delete from friends</button>
         </form>
      <?php } else { ?>

         <?php if (DB::query('SELECT friendr_id FROM friend_requests WHERE friendr_senderid = :senderid AND friendr_receiverid = :receiverid', [':senderid' => Auth::loggedin(), ':receiverid' => $profileUser['id']])[0]['friendr_id']) { ?>
            <form method="post" action="">
               <button type="submit" name="cancelFR">cancel friend request</button>
            </form>
         <?php } else if (!DB::query('SELECT friendr_id FROM friend_requests WHERE (friendr_senderid = :senderid AND friendr_receiverid = :receiverid) OR (friendr_senderid = :receiverid AND friendr_receiverid = :senderid)', [':senderid' => Auth::loggedin(), ':receiverid' => $profileUser['id']])[0]['friendr_id']) { ?>
            <form method="post" action="">
               <input type="text" name="sendFRMessage" placeholder="message">
               <button type="submit" name="sendFR">add to friends</button>
            </form>
         <?php } ?>

         <?php if (DB::query('SELECT friendr_id FROM friend_requests WHERE friendr_senderid = :senderid AND friendr_receiverid = :receiverid', [':senderid' => $profileUser['id'], ':receiverid' => Auth::loggedin()])[0]['friendr_id']) { ?>
            <p><?php
            echo $profileUser['user_name'] . " wysłał Tobie zaproszenie do grona znajomych!";
            ?></p>
            <form method="post" action="">
               <button type="submit" name="acceptFR">accept request</button>
            </form>
            <form method="post" action="">
               <button type="submit" name="cancelFR">cancel friend request</button>
            </form>
         <?php } ?>

      <?php } ?>
   </div>
   <?php } ?>

   <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
</body>
</html>
