<?php
require('../autoload.php');
if (isset($_POST['APIload'])) {
   $userid = $_POST['APIUserId'];
   $loggedinuser = $_POST['APILoggedinId'];

   if (DB::query('SELECT friends_id FROM friends WHERE (friends_userid = :userid AND friends_friendid = :friendid) OR (friends_userid = :friendid AND friends_friendid = :userid)', [':userid' => $loggedinuser, ':friendid' => $userid])[0]['friends_id']) { ?>
      <?php if (!DB::query('SELECT followers_id FROM followers WHERE followers_userid = :userid AND followers_followerid = :followerid AND followers_type = :type', [':userid' => $loggedinuser, ':followerid' => $userid, ':type' => 1])[0]['followers_id']) { ?>
         <form method="post" id="STARTFollowingForm">
            <button type="submit" id="STARTFollowingBtn">follow</button>
         </form>
         <script>
            $('#STARTFollowingForm').submit(function(e) {
               e.preventDefault();
               let userid = <?php echo $userid; ?>;
               let loggedUserid = <?php echo $loggedinuser; ?>;
               $.post( "http://localhost/facebook/app/api/friends.php", { STARTuserid: userid, STARTloggedUserid: loggedUserid })
               .done(function( data ) {
                  $('#ctaFollowing').html(data);
               });
            });
         </script>
      <?php } else { ?>
         <form method="post" id="STOPFollowingForm">
            <button type="submit" id="STOPFollowingBtn">follow</button>
         </form>
         <script>
            $('#STOPFollowingForm').submit(function(e) {
               e.preventDefault();
               let userid = <?php echo $userid; ?>;
               let loggedUserid = <?php echo $loggedinuser; ?>;
               $.post( "http://localhost/facebook/app/api/friends.php", { STOPuserid: userid, STOPloggedUserid: loggedUserid })
               .done(function( data ) {
                  $('#ctaFollowing').html(data);
               });
            });
         </script>
      <?php }
   }
}

if (isset($_POST['STARTuserid']) && isset($_POST['STARTloggedUserid'])) {
   $userid = $_POST['STARTuserid'];
   $loggedinuser = $_POST['STARTloggedUserid'];

   $status = Follow::cancelFriendRequest($requestid, $loggedUserid, $userid);
   if ($status == false) {
      exit();
   }
}
