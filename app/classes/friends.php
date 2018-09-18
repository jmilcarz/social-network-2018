<?php

class Friends
{

   public static function sendFriendRequest($senderid, $receiverid, $message) {
      if (!is_numeric($senderid) || !is_numeric($receiverid)) {
         echo 'Senderid & receiverid must be an intiger!';
         return false;
      } else if (DB::query('SELECT friendr_id FROM friend_requests WHERE (friendr_senderid = :senderid AND friendr_receiverid = :receiverid) OR (friendr_senderid = :receiverid AND friendr_receiverid = :senderid)', [':senderid' => $senderid,':receiverid' => $receiverid])[0]['friendr_id']) {
         echo 'That request already exists! You can accept it or resend it.';
         return false;
      } else if (strlen($message) < 3 || strlen($message) >= 32) {
         echo 'Invalid message. min: 3, max: 32';
         return false;
      }
      $bdate = date('Y-m-d H:i:s');

      DB::query('INSERT INTO friend_requests VALUES (\'\', :senderid, :receiverid, :dos, :message)', [':senderid' => $senderid, ':receiverid' => $receiverid, ':dos' => $bdate, ':message' => $message]);
      ## echo ';
      return true;
   }

   public static function acceptFriendRequest($requestid, $senderid, $receiverid) {
      if (!is_numeric($senderid) || !is_numeric($receiverid)) {
         echo 'Senderid & receiverid must be an intiger!';
         return false;
      }
      if (DB::query('SELECT friendr_id FROM friend_requests WHERE (friendr_senderid = :senderid AND friendr_receiverid = :receiverid) OR (friendr_senderid = :receiverid AND friendr_receiverid = :senderid)', [':senderid' => $senderid,':receiverid' => $receiverid])[0]['friendr_id']) {
         DB::query('DELETE FROM friend_requests WHERE friendr_id = :friendrid AND (friendr_senderid = :senderid AND friendr_receiverid = :receiverid) OR (friendr_senderid = :receiverid AND friendr_receiverid = :senderid)', [':friendrid' => $requestid,':senderid' => $senderid,':receiverid' => $receiverid]);
         $today = date('Y-m-d');
         $bdate = date('Y-m-d H:i:s');
         DB::query('INSERT INTO friends VALUES (\'\', :userid, :friendid, :since, :acceptdate)', [':userid' => $senderid, ':friendid' => $receiverid, ':since' => $today, ':acceptdate' => $bdate]);
         # '
         return true;
      } else {
         return false;
      }
   }

   public static function cancelFriendRequest($requestid, $senderid, $receiverid) {
      if (!is_numeric($senderid) || !is_numeric($receiverid)) {
         echo 'Senderid & receiverid must be an intiger!';
         return false;
      }
      if (DB::query('SELECT friendr_id FROM friend_requests WHERE (friendr_senderid = :senderid AND friendr_receiverid = :receiverid) OR (friendr_senderid = :receiverid AND friendr_receiverid = :senderid)', [':senderid' => $senderid,':receiverid' => $receiverid])[0]['friendr_id']) {
         DB::query('DELETE FROM friend_requests WHERE friendr_id = :friendrid AND (friendr_senderid = :senderid AND friendr_receiverid = :receiverid) OR (friendr_senderid = :receiverid AND friendr_receiverid = :senderid)', [':friendrid' => $requestid,':senderid' => $senderid,':receiverid' => $receiverid]);
         return true;
      } else {
         return false;
      }

   }

   public static function deleteFriend($friendid, $userid) {
      if (!is_numeric($friendid) || !is_numeric($userid)) {
         echo 'Senderid & receiverid must be an intiger!';
         return false;
      }
      if (DB::query('SELECT friends_id FROM friends WHERE (friends_userid = :userid AND friends_friendid = :friendid) OR (friends_userid = :friendid AND friends_friendid = :userid)', [':userid' => $userid, ':friendid' => $friendid])[0]['friends_id']) {
         DB::query('DELETE FROM friends WHERE (friends_userid = :userid AND friends_friendid = :friendid) OR (friends_userid = :friendid AND friends_friendid = :userid)', [':userid' => $userid, ':friendid' => $friendid]);
      } else {
         echo 'you cannot delete';
      }
   }

   public static function displayFriendList($userid) {

   }

   public static function displayFriendsCtas($userid, $loggedinuser) {

   }

}
