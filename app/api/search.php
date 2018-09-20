<?php
   if(isset($_POST['query'])) {
      require('../autoload.php');

      $query = Security::check($_POST['query']);
      if (strlen($query) < 2) {
         echo 'query is too short!';
         exit();
      }

      $users = DB::query("SELECT users.user_name, users.user_avatar, users.user_username FROM users WHERE user_name = :query OR (user_firstname LIKE CONCAT(:query, '%') OR user_lastname LIKE CONCAT(:query, '%')) ORDER BY user_name ASC", [':query' => $query]);
      if (count($users) < 1) {
         echo 'No result found :/';
      }
      foreach ($users as $user) {
      ?>
      <a href='profile/<?php echo $user['user_username']; ?>'>
         <img src="<?php
         if ($user['user_avatar'] == 'no-photo') {
            echo 'https://www.ischool.berkeley.edu/sites/default/files/default_images/avatar.jpeg';
         } else {
            echo 'http://localhost/facebook/storage/pictures/%E2%81%A9' . $user['user_avatar'];
         }
         ?>" alt="" width="50" height="50">
         <?php echo $user['user_name']; ?>
      </a><hr>
      <?php
      }
   }
