<style> .users {display: flex;flex-wrap: wrap;} .user {width: 450px;background: #ecebec;display: flex;height: 120px;align-items: center;margin: 15px;box-sizing: border-box;padding: 15px;} .user img {height: 100px;width: 100px;object-fit: cover;}.user .user-info {display: flex;flex-direction: column;height: calc(100% - 10px);margin-left: 10px;}.user .user-info h1 {margin: 0;font-size: 22px;}.user .user-info h2 {margin: 5px 0 0 0;font-size: 16px;}.user .user-info p {margin: 0;font-size: 14px;margin-top: 10px;} </style>
<?php
### This code will only work with /register.php! ###
require('../../autoload.php');
session_start();

// get all users based on country & city
$users = DB::query('SELECT users.id AS user_id, user_username, user_name, user_avatar, user_backgroundphoto, useri_country, useri_city FROM users, user_info WHERE (user_email <> :email AND user_password <> :password) AND useri_user_id = users.id AND useri_country = :country ORDER BY useri_city LIMIT 20', [':password' =>$_SESSION['password'], ':email' => $_SESSION['email'],':country' => $_SESSION['country']]);

?><div class="users">
<?php foreach ($users as $user) { ?>
   <div class="user">
      <?php
      if ($user['user_avatar'] == 'no-photo') {
         echo '<img src="https://www.steakenbierrestaurant.nl/wp-content/uploads/2017/06/user_default.png" alt="">';
      }else {
         echo '<img src="http://localhost:8888/facebook/storage/pictures/%E2%81%A9' . $user['user_avatar'] . '" alt="">';
      }
      ?>
      <div class="user-info">
         <h1><?php echo $user['user_name']; ?></h1>
         <h2>@<?php echo $user['user_username']; ?></h2>
         <p><?php echo $user['useri_city'] . ', ' . $user['useri_country']; ?></p>
      </div>
      <div class="cta" id= "ctaBoxId<?php echo $user['user_id']; ?>">
         <?php
            if (DB::query('SELECT friendr_id FROM friend_requests WHERE (friendr_senderid = :senderid AND friendr_receiverid = :receiverid) OR (friendr_senderid = :receiverid AND friendr_receiverid = :senderid)', [':senderid' => $_SESSION['userid'], ':receiverid' => $user['user_id']])[0]['friendr_id']) {
               ?>
               <form id="form<?php echo $user['user_id']; ?>">
                  <button type="submit" id="cancel<?php echo $userid; ?>">cancel friend request</button>
               </form>
               <script>
                  $('#form<?php echo $user['user_id']; ?>').on('submit', function(e) {
                     e.preventDefault();
                     let userid = <?php echo $user['user_id']; ?>;
                     let loggedUserid = <?php echo $_SESSION['userid']; ?>;
                     $.post( "./app/api/auth/friendRequestHandler.php", { CANCELuserid: userid, CANCELloggedUserid: loggedUserid })
                     .done(function( data ) {
                        $('#ctaBoxId<?php echo $user['user_id']; ?>').html(data);
                     });
                  });
               </script>
               <?php
            } else {
         ?>
            <form id="form<?php echo $user['user_id']; ?>">
               <button type="submit" id="send<?php echo $user['user_id']; ?>">add to friends</button>
            </form>
            <script>
               $('#form<?php echo $user['user_id']; ?>').on('submit', function(e) {
                  e.preventDefault();
                  let userid = <?php echo $user['user_id']; ?>;
                  let loggedUserid = <?php echo $_SESSION['userid']; ?>;
                  $.post( "./app/api/auth/friendRequestHandler.php", { SENDuserid: userid, SENDloggedUserid: loggedUserid })
                  .done(function( data ) {
                     $('#ctaBoxId<?php echo $user['user_id']; ?>').html(data);
                  });
               });
            </script>
         <?php } ?>
      </div>
   </div>
<?php } ?>
</div>
