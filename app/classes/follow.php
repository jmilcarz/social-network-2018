<?php

/**
 * type
 * 0 -> none
 * 1 -> user
 * 2 -> page
 * 3 -> group ??
 * ...
 */

class Follow {

   public static function startFollowing($userid, $followerid, $type) {
      if (!DB::query('SELECT followers_id FROM followers WHERE followers_userid = :userid AND followers_followerid = :followerid AND followers_type = :type', [':userid' => $userid, ':followerid' => $followerid, ':type' => $type])[0]['followers_id']) {
         $bdate = date('Y-m-d H:i:s');
         DB::query('INSERT INTO followers VALUES (\'\', :userid, :followerid, :type, :bdate)', [':userid' => $userid, ':followerid' => $followerid, ':type' => $type, ':bdate' => $bdate]);
         # '
         return true;
      }else {
         echo 'u can\'t follow';
         return false;
      }
   }

   public static function stopFollowing($userid, $followerid) {
      if (!DB::query('SELECT followers_id FROM followers WHERE followers_userid = :userid AND followers_followerid = :followerid AND followers_type = :type', [':userid' => $userid, ':followerid' => $followerid, ':type' => $type])[0]['followers_id']) {
         DB::query('DELETE FROM followers WHERE followers_userid = :userid AND followers_followerid = :followerid', ['userid' => $userid, ':followerid' => $followerid]);
         return true;
      }else {
         echo 'u can\'t unfollow';
         return false;
      }
   }

}
