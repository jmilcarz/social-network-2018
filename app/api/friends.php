<?php
require('../autoload.php');
if (isset($_POST['APIload'])) {
   $userid = $_POST['APIUserId'];
   $loggedinuser = $_POST['APILoggedinId'];

   if (DB::query('SELECT friends_id FROM friends WHERE (friends_userid = :userid AND friends_friendid = :friendid) OR (friends_userid = :friendid AND friends_friendid = :userid)', [':userid' => $loggedinuser, ':friendid' => $userid])[0]['friends_id']) { ?>
      <form method="post" id="REMOVEfriendForm">
         <button type="submit" name="removeFR" id="REMOVEfriendFormBtn">remove from friends</button>
      </form>
      <script>
         $('#REMOVEfriendForm').submit(function(e) {
            e.preventDefault();
            let userid = <?php echo $userid; ?>;
            let loggedUserid = <?php echo $loggedinuser; ?>;
            $.post( "http://localhost/facebook/app/api/friends.php", { REMOVEuserid: userid, REMOVEloggedUserid: loggedUserid })
            .done(function( data ) {
               $('#friendActionsCTAS').html(data);
            });
         });
      </script>
      <div id="friendFollowingsActionsCTAS"></div>
      <script>
         <?php if (DB::query('SELECT friends_id FROM friends WHERE (friends_userid = :userid AND friends_friendid = :friendid) OR (friends_userid = :friendid AND friends_friendid = :userid)', [':userid' => $loggedinuser, ':friendid' => $userid])[0]['friends_id']) { ?>
            $(function() {
               $.post( "http://localhost/facebook/app/api/follow.php", { APIload: true, APIUserId: <?php echo $userid; ?>, APILoggedinId: <?php echo $loggedinuser; ?> })
               .done(function( data ) {
                  $('#friendFollowingsActionsCTAS').html(data);
               });
            });
         <?php } ?>
      </script>
   <?php } else { ?>

      <?php if (DB::query('SELECT friendr_id FROM friend_requests WHERE friendr_senderid = :senderid AND friendr_receiverid = :receiverid', [':senderid' => $loggedinuser, ':receiverid' => $userid])[0]['friendr_id']) { ?>
         <form method="post" id="CANCELfriendForm">
            <button type="submit" name="cancelFR" id="CANCELfriendFormBtn">cancel friend request</button>
         </form>
         <script>
            $('#CANCELfriendForm').submit(function(e) {
               e.preventDefault();
               let userid = <?php echo $userid; ?>;
               let loggedUserid = <?php echo $loggedinuser; ?>;
               $.post( "http://localhost/facebook/app/api/friends.php", { CANCELuserid: userid, CANCELloggedUserid: loggedUserid })
               .done(function( data ) {
                  $('#friendActionsCTAS').html(data);
               });
            });
         </script>
      <?php } else if (!DB::query('SELECT friendr_id FROM friend_requests WHERE (friendr_senderid = :senderid AND friendr_receiverid = :receiverid) OR (friendr_senderid = :receiverid AND friendr_receiverid = :senderid)', [':senderid' => $loggedinuser, ':receiverid' => $userid])[0]['friendr_id']) { ?>
         <form method="post" id="SENDfriendForm">
            <input type="text" name="sendFRMessage" placeholder="message" id="SENDfriendFormMessage">
            <button type="submit" name="sendFR" id="SENDfriendFormBtn">add to friends</button>
         </form>
         <script>
            $('#SENDfriendForm').submit(function(e) {
               e.preventDefault();
               let userid = <?php echo $userid; ?>;
               let loggedUserid = <?php echo $loggedinuser; ?>;
               let message = $("#SENDfriendFormMessage").val();
               $.post( "http://localhost/facebook/app/api/friends.php", { SENDuserid: userid, SENDloggedUserid: loggedUserid, SENDMessage: message })
               .done(function( data ) {
                  $('#friendActionsCTAS').html(data);
               });
            });
         </script>
      <?php } # SEND ?>

      <?php if (DB::query('SELECT friendr_id FROM friend_requests WHERE friendr_senderid = :senderid AND friendr_receiverid = :receiverid', [':senderid' => $userid, ':receiverid' => $loggedinuser])[0]['friendr_id']) { ?>
         <p><?php
         $message = DB::query('SELECT friendr_message FROM friend_requests WHERE friendr_senderid = :senderid AND friendr_receiverid = :receiverid', [':senderid' => $userid, ':receiverid' => $loggedinuser])[0]['friendr_message'];
         echo $profileUser['user_name'] . " sent you a friend request! <br> " . $message;
         ?></p>
         <form method="post" id="ACCEPTfriendForm">
            <button type="submit" name="acceptFR" id="ACCEPTfriendFormBtn">accept</button>
         </form>
         <script>
            $('#ACCEPTfriendForm').submit(function(e) {
               e.preventDefault();
               let userid = <?php echo $userid; ?>;
               let loggedUserid = <?php echo $loggedinuser; ?>;
               $.post( "http://localhost/facebook/app/api/friends.php", { ACCEPTuserid: userid, ACCEPTloggedUserid: loggedUserid })
               .done(function( data ) {
                  $('#friendActionsCTAS').html(data);
               });
            });
         </script>
         <form method="post" id="REJECTfriendForm">
            <button type="submit" name="rejectFR" id="REJECTfriendFormBtn">reject friend request</button>
         </form>
         <script>
            $('#REJECTfriendForm').submit(function(e) {
               e.preventDefault();
               let userid = <?php echo $userid; ?>;
               let loggedUserid = <?php echo $loggedinuser; ?>;
               $.post( "http://localhost/facebook/app/api/friends.php", { REJECTuserid: userid, REJECTloggedUserid: loggedUserid })
               .done(function( data ) {
                  $('#friendActionsCTAS').html(data);
               });
            });
         </script>
      <?php # ACCEPT
      }
   }
}

# -- start -- send
if (isset($_POST['SENDuserid']) && isset($_POST['SENDloggedUserid'])) { # SEND & CANCEL
   $userid = Security::check($_POST['SENDuserid']);
   $loggedUserid = Security::check($_POST['SENDloggedUserid']);

   if (isset($_POST['SENDMessage'])) {
      $message = Security::check($_POST['SENDMessage']);
      $status = Friends::sendFriendRequest($loggedUserid, $userid, $message);
   } else {
      $status = Friends::sendFriendRequest($loggedUserid, $userid, "standard message");
   }

   if ($status == false) {
      exit();
   }

   ### cta box ### ?>
   <form method="post" id="CANCELfriendForm">
      <button type="submit" name="cancelFR" id="CANCELfriendFormBtn">cancel friend request</button>
   </form>
   <script>
      $('#CANCELfriendForm').submit(function(e) {
         e.preventDefault();
         let userid = <?php echo $userid; ?>;
         let loggedUserid = <?php echo $loggedUserid; ?>;
         $.post( "http://localhost/facebook/app/api/friends.php", { CANCELuserid: userid, CANCELloggedUserid: loggedUserid })
         .done(function( data ) {
            $('#friendActionsCTAS').html(data);
         });
      });
   </script>
   <?php ### -cta box- ###
}
# -- end -- send

# -- start -- cancel request
if (isset($_POST['CANCELuserid']) && isset($_POST['CANCELloggedUserid'])) { # CANCEL & SEND
   $userid = Security::check($_POST['CANCELuserid']);
   $loggedUserid = Security::check($_POST['CANCELloggedUserid']);
   $requestid = DB::query('SELECT friendr_id FROM friend_requests WHERE (friendr_senderid = :senderid AND friendr_receiverid = :receiverid) OR (friendr_senderid = :receiverid AND friendr_receiverid = :senderid)', [':senderid' => $userid, ':receiverid' => $loggedUserid])[0]['friendr_id'];

   $status = Friends::cancelFriendRequest($requestid, $loggedUserid, $userid);
   if ($status == false) {
      exit();
   }

   ### cta box ### ?>
   <form method="post" id="SENDfriendForm">
      <input type="text" name="sendFRMessage" placeholder="message" id="SENDfriendFormMessage">
      <button type="submit" name="sendFR" id="SENDfriendFormBtn">add to friends</button>
   </form>
   <script>
      $('#SENDfriendForm').submit(function(e) {
         e.preventDefault();
         let userid = <?php echo $userid; ?>;
         let loggedUserid = <?php echo $loggedUserid; ?>;
         let message = $("#SENDfriendFormMessage").val();
         $.post( "http://localhost/facebook/app/api/friends.php", { SENDuserid: userid, SENDloggedUserid: loggedUserid, SENDMessage: message })
         .done(function( data ) {
            $('#friendActionsCTAS').html(data);
         });
      });
   </script>
   <?php ### -cta box- ###
}
# -- end -- cancel request

# -- start -- accept request
if (isset($_POST['ACCEPTuserid']) && isset($_POST['ACCEPTloggedUserid'])) { # ACCEPT & REMOVE
   $userid = Security::check($_POST['ACCEPTuserid']);
   $loggedUserid = Security::check($_POST['ACCEPTloggedUserid']);
   $requestid = DB::query('SELECT friendr_id FROM friend_requests WHERE (friendr_senderid = :senderid AND friendr_receiverid = :receiverid) OR (friendr_senderid = :receiverid AND friendr_receiverid = :senderid)', [':senderid' => $userid, ':receiverid' => $loggedUserid])[0]['friendr_id'];

   $status = Friends::acceptFriendRequest($requestid, $userid, $loggedUserid);
   if ($status == false) {
      exit();
   }

   ### cta box ###
   if (DB::query('SELECT friends_id FROM friends WHERE (friends_userid = :userid AND friends_friendid = :friendid) OR (friends_userid = :friendid AND friends_friendid = :userid)', [':userid' => $loggedUserid, ':friendid' => $userid])[0]['friends_id']) { ?>
      <form method="post" id="REMOVEfriendForm">
         <button type="submit" name="removeFR" id="REMOVEfriendFormBtn">remove from friends</button>
      </form>
      <script>
         $('#REMOVEfriendForm').submit(function(e) {
            e.preventDefault();
            let userid = <?php echo $userid; ?>;
            let loggedUserid = <?php echo $loggedUserid; ?>;
            $.post( "http://localhost/facebook/app/api/friends.php", { REMOVEuserid: userid, REMOVEloggedUserid: loggedUserid })
            .done(function( data ) {
               $('#friendActionsCTAS').html(data);
            });
         });
      </script>
      <div id="friendFollowingsActionsCTAS"></div>
      <script>
         function loadFollows() {
            $.post( "http://localhost/facebook/app/api/follow.php", { APIload: true, APIUserId: <?php echo $userid; ?>, APILoggedinId: <?php echo $loggedUserid; ?> })
            .done(function( data ) {
               $('#friendFollowingsActionsCTAS').html(data);
            });
         }
         loadFollows();
      </script>
   <?php }### -cta box- ###
}
# -- end -- accept request

# -- start -- remove friend
if (isset($_POST['REMOVEuserid']) && isset($_POST['REMOVEloggedUserid'])) { # REMOVE & SEND
   $userid = Security::check($_POST['REMOVEuserid']);
   $loggedUserid = Security::check($_POST['REMOVEloggedUserid']);
   $requestid = DB::query('SELECT friendr_id FROM friend_requests WHERE (friendr_senderid = :senderid AND friendr_receiverid = :receiverid) OR (friendr_senderid = :receiverid AND friendr_receiverid = :senderid)', [':senderid' => $userid, ':receiverid' => $loggedUserid])[0]['friendr_id'];

   $status = Friends::removeFriend($userid, $loggedUserid);
   if ($status == false) {
      exit();
   }

   ### cta box ### ?>
   <form method="post" id="SENDfriendForm">
      <input type="text" name="sendFRMessage" placeholder="message" id="SENDfriendFormMessage">
      <button type="submit" name="sendFR" id="SENDfriendFormBtn">add to friends</button>
   </form>
   <script>
      $('#SENDfriendForm').submit(function(e) {
         e.preventDefault();
         let userid = <?php echo $userid; ?>;
         let loggedUserid = <?php echo $loggedUserid; ?>;
         let message = $("#SENDfriendFormMessage").val();
         $.post( "http://localhost/facebook/app/api/friends.php", { SENDuserid: userid, SENDloggedUserid: loggedUserid, SENDMessage: message })
         .done(function( data ) {
            $('#friendActionsCTAS').html(data);
         });
      });
   </script>
   <?php ### -cta box- ###
}
# -- end -- remove friend

# -- start -- reject request
if (isset($_POST['REJECTuserid']) && isset($_POST['REJECTloggedUserid'])) { # REJECT & SEND
   $userid = Security::check($_POST['REJECTuserid']);
   $loggedUserid = Security::check($_POST['REJECTloggedUserid']);
   $requestid = DB::query('SELECT friendr_id FROM friend_requests WHERE (friendr_senderid = :senderid AND friendr_receiverid = :receiverid) OR (friendr_senderid = :receiverid AND friendr_receiverid = :senderid)', [':senderid' => $userid, ':receiverid' => $loggedUserid])[0]['friendr_id'];

   $status = Friends::cancelFriendRequest($requestid, $loggedUserid, $userid);
   if ($status == false) {
      exit();
   }

   ### cta box ### ?>
   <form method="post" id="SENDfriendForm">
      <input type="text" name="sendFRMessage" placeholder="message" id="SENDfriendFormMessage">
      <button type="submit" name="sendFR" id="SENDfriendFormBtn">add to friends</button>
   </form>
   <script>
      $('#SENDfriendForm').submit(function(e) {
         e.preventDefault();
         let userid = <?php echo $userid; ?>;
         let loggedUserid = <?php echo $loggedUserid; ?>;
         let message = $("#SENDfriendFormMessage").val();
         $.post( "http://localhost/facebook/app/api/friends.php", { SENDuserid: userid, SENDloggedUserid: loggedUserid, SENDMessage: message })
         .done(function( data ) {
            $('#friendActionsCTAS').html(data);
         });
      });
   </script>
   <?php ### -cta box- ###
}
# -- end -- reject request
