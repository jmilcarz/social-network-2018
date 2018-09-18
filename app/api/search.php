<?php
   if(isset($_POST['query'])) {
      require('../autoload.php');
      $pdo = new PDO('mysql:host=localhost;dbname=newfb;charset=utf8mb4', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'"));

      $query = Security::check($_POST['query']);
      if (strlen($query) < 2) {
         echo 'query is too short!';
         exit();
      }
      $terms = explode(' ', $query);

      $x = 0;
      $construct = "";
      $params = array();
      foreach ($terms as $term) {
         $x++;
         if ($x == 1) {
            $construct .= "users.user_firstname LIKE CONCAT('%',:search$x,'%') OR users.user_lastname LIKE CONCAT('%',:search$x,'%') OR users.user_username LIKE CONCAT('%',:search$x,'%')";
         }else {
            $construct .= " AND users.user_firstname LIKE CONCAT('%',:search$x,'%') OR users.user_lastname LIKE CONCAT('%',:search$x,'%') OR users.user_username LIKE CONCAT('%',:search$x,'%')";
         }
         $params["search$x"] = $term;
      }


      $results = $pdo->prepare("SELECT users.user_name, users.user_name, users.user_avatar, users.user_username FROM users WHERE $construct ORDER BY users.id");
      $results->execute($params);

      if ($results->rowCount() == 0) {
           echo "0 results found <hr>";
      }else {
           echo $results->rowCount() . " results found <br>";
      }
      foreach ($results->fetchAll() as $result) { ?>
         <a href='profile/<?php echo $result['user_username']; ?>'>
            <img src="<?php
            if ($result['user_avatar'] == 'no-photo') {
               echo 'https://www.ischool.berkeley.edu/sites/default/files/default_images/avatar.jpeg';
            } else {
               echo 'http://localhost/facebook/storage/pictures/%E2%81%A9' . $profileUser['user_avatar'];
            }
            ?>" alt="" width="50" height="50">
            <?php echo $result['user_name']; ?>
         </a><hr>
      <?php }
   }
