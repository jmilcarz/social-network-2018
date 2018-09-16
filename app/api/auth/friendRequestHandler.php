<?php
if (isset($_POST['SENDuserid']) && isset($_POST['SENDloggedUserid'])) {
   require('../../classes/db.php');
   require('../../classes/security.php');
   session_start();
   $userid = Security::check($_POST['SENDuserid']);
   $loggedUserid = Security::check($_POST['SENDloggedUserid']);


   // TODO: here send friend request

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
   session_start();
   $userid = Security::check($_POST['CANCELuserid']);
   $loggedUserid = Security::check($_POST['CANCELloggedUserid']);

   // TODO: here cancel friend request

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
