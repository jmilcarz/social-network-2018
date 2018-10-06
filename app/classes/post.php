<?php

class Post
{

   private static $privacies = ['public', 'private', 'friends'];

   public static function createNew($userid, $content, $privacy) {

      if (!is_numeric($userid)) {
         echo 'User Id must be an intiger!';
         return false;
      } else if (!DB::query('SELECT id FROM users WHERE id = :userid', [':userid' => $userid])[0]['id']) {
         echo 'User with this user id does not exists!';
         return false;
      } else if (strlen($content) <= 3 || strlen($content) >= 512) {
         echo 'Post cannot be shorter than 3 characters & longer than 512 characters!';
         return false;
      } else if (!in_array($privacy, self::$privacies)) {
         echo 'Incorrent privacy!';
         return false;
      }

      if ($privacy == "public") {
         $privacy = 1;
      } else if ($privacy == "private") {
         $privacy = 0;
      } else if ($privacy == "friends") {
         $privacy = 2;
      }

      $tags = [];
      $bdate = date('Y-m-d H:i:s');

      DB::query('INSERT INTO posts VALUES (\'\', :userid, :content, :tags, :privacy, :likes, :comments, :shares, :bdate)', [':userid' => $userid, ':content' => $content, ':tags' => $tags, ':privacy' => $privacy, ':likes' => 0, ':comments' => 0, ':shares' => 0, ':bdate' => $bdate]);
      # '
      echo '<p>Post successfuly published!</p>';
      return true;
   }

}
