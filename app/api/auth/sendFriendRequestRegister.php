<?php

echo $_POST['name'];
echo 'lol';

if (isset($_POST['userid']) && isset($_POST['loggedUserid'])) {
   require('../../classes/db.php');
   require('../../classes/security.php');
   session_start();
   $userid = Security::check($_POST['userid']);
   $loggedUserid = Security::check($_POST['loggedUserid']);

   echo 'userid: ' . $userid . '<br>loggedinuserid: ' . $loggedUserid;
}
