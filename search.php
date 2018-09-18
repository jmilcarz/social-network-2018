<?php require('app/autoload.php');

if (!Auth::loggedin()) {
   header('Location: index.php');
   exit();
}

if (isset($_GET['q'])) {
   $pdo = new PDO('mysql:host=localhost;dbname=newfb;charset=utf8mb4', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'"));

   $query = Security::check($_GET['q']);
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


   $results = $pdo->prepare("SELECT users.user_name, users.user_name, users.user_avatar, users.user_username FROM users WHERE $construct ORDER BY user_name DESC");
   $results->execute($params);

   ?>
      <!DOCTYPE html>
      <html lang="en">
      <head>
         <?php require('app/incs/head-metas.inc.php'); ?>
         <title><?php echo $query; ?> - | search</title>
         <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

      </head>
      <body>
         <h1>Results for: <?php echo $query; ?></h1>
         <form id="search-form">
            <input type="text" placeholder="Search" id="search-input">
            <button type="submit">-></button>
         </form>
         <script>
            $('#search-form').submit(function(e) {
               e.preventDefault();
               let searchphrase = $("#search-input").val();
               $.ajax({
                  url: "app/api/search.php",
                  type: 'POST',
                  async: true,
                  cache: true,
                  timeout: 30000,
                  data: {query: searchphrase},
                  beforeSend: function() {
                     $('#results').html("Loading");
                  },
                  error: function() {
                     $('#results').html("Error");
                  },
                  success: function(data) {
                     $('#results').html(data);
                  }
               });
            });
         </script>
         <div id="results">
            <?php

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

            ?>
         </div>
      </body>
      </html>
   <?php
   exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <?php require('app/incs/head-metas.inc.php'); ?>
   <title>Search</title>
   <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

</head>
<body>
   <h1>Search</h1>
   <form id="search-form">
      <input type="text" placeholder="Search" id="search-input">
      <button type="submit">-></button>
   </form>
   <script>
      $('#search-form').submit(function(e) {
         e.preventDefault();
         let searchphrase = $("#search-input").val();
         $.ajax({
            url: "app/api/search.php",
            type: 'POST',
            async: false,
            cache: false,
            timeout: 30000,
            data: {query: searchphrase},
            error: function() {
               $('#results').html("Error");
            },
            success: function(data) {
               $('#results').html(data);
            }
         });
      });
   </script>
   <div id="results"></div>
</body>
</html>
