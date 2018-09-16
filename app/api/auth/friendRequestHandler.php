<?php
if (isset($_POST['SENDuserid']) && isset($_POST['SENDloggedUserid'])) {
   require('../../classes/db.php');
   require('../../classes/security.php');
   require('../../classes/friends.php');
   session_start();
   $userid = Security::check($_POST['SENDuserid']);
   $loggedUserid = Security::check($_POST['SENDloggedUserid']);

   $status = Friends::sendFriendRequest($loggedUserid, $userid, "standard message");

   if ($status == false) {
      echo 'dupa';
      exit();
   }else {
      echo "hurra!";
   }

   ### cta box ### ?>
   <form id="form<?php echo $userid; ?>">
      <button type="submit" id="cancel<?php echo $userid; ?>">cancel friend request</button>
   </form>
   <script>
      $('#form<?php echo $userid; ?>').on('submit', function(e) {
         e.preventDefault();
         let userid = <?php echo $userid; ?>;
         let loggedUserid = <?php echo $loggedUserid; ?>;
         $.post( "./app/api/auth/friendRequestHandler.php", { CANCELuserid: userid, CANCELloggedUserid: loggedUserid })
         .done(function( data ) {
            $('#ctaBoxId<?php echo $userid; ?>').html(data);
         });
      });
   </script>
   <?php ### -cta box- ###
}

if (isset($_POST['CANCELuserid']) && isset($_POST['CANCELloggedUserid'])) {
   require('../../classes/db.php');
   require('../../classes/security.php');
   require('../../classes/friends.php');
   session_start();
   $userid = Security::check($_POST['CANCELuserid']);
   $loggedUserid = Security::check($_POST['CANCELloggedUserid']);
   $requestid = DB::query('SELECT friendr_id FROM friend_requests WHERE (friendr_senderid = :senderid AND friendr_receiverid = :receiverid) OR (friendr_senderid = :receiverid AND friendr_receiverid = :senderid)', [':senderid' => $userid, ':receiverid' => $loggedUserid])[0]['friendr_id'];

   $status = Friends::cancelFriendRequest($requestid, $loggedUserid, $userid);

   if ($status == false) {
      echo 'dupa';
      exit();
   }else {
      echo "hurra!";
   }

   ### cta box ### ?>
   <form id="form<?php echo $userid; ?>">
      <button type="submit" id="send<?php echo $userid; ?>">add to friends</button>
   </form>
   <script>
      $('#form<?php echo $userid; ?>').on('submit', function(e) {
         e.preventDefault();
         let userid = <?php echo $userid; ?>;
         let loggedUserid = <?php echo $loggedUserid; ?>;
         $.post( "./app/api/auth/friendRequestHandler.php", { SENDuserid: userid, SENDloggedUserid: loggedUserid })
         .done(function( data ) {
            $('#ctaBoxId<?php echo $userid; ?>').html(data);
         });
      });
   </script>
   <?php ### -cta box- ###
}
