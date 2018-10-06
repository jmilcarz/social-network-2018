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

      $topics = self::getTopics($content);
      $bdate = date('Y-m-d H:i:s');

      DB::query('INSERT INTO posts VALUES (\'\', :userid, :content, :topics, :privacy, :likes, :comments, :shares, :bdate)', [':userid' => $userid, ':content' => $content, ':topics' => $topics, ':privacy' => $privacy, ':likes' => 0, ':comments' => 0, ':shares' => 0, ':bdate' => $bdate]);
      # '
      echo '<p>Post successfuly published!</p>';
      return true;
   }

   public static function getTopics($text) {
      $text = explode(" ", $text);
      $topics = "";
      foreach ($text as $word) {
         if (substr($word, 0, 1) == "#") {
            $topics .= substr($word, 1).",";
         }
      }
      return $topics;
   }

   public static function link_add($text) {
      $text = explode(" ", $text);
      $newstring = "";
      foreach ($text as $word) {
         if (substr($word, 0, 1) == "@") {
            $newstring .= "<a href='profile/".substr($word, 1)."'>".htmlspecialchars($word)."</a> ";
         } else if (substr($word, 0, 1) == "#") {
            $newstring .= "<a href='topics.php?topic=".substr($word, 1)."'>".htmlspecialchars($word)."</a> ";
         } else {
            $newstring .= htmlspecialchars($word)." ";
         }
      }
      return $newstring;
   }

}
