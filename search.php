<?php require('app/autoload.php');

if (!Auth::loggedin()) {
   header('Location: index.php');
   exit();
}

if (isset($_GET['q'])) {
   $query = Security::check($_GET['q']);
   if (strlen($query) < 2) {
      echo 'query is too short!';
      exit();
   }

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
         <form id="search-form" method="post">
            <input type="text" placeholder="Search" id="search-input">
            <button type="submit">-></button>
         </form>
         <script>
            $('#search-form').submit(function() {
               e.preventDefault();
               let searchphrase = $("#search-input").val();
               $.ajax({
                  url: "app/api/search.php",
                  type: 'GET',
                  async: true,
                  cache: true,
                  timeout: 30000,
                  data: {q: searchphrase},
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
            $users = DB::query('SELECT users.user_name, users.user_avatar, users.user_username FROM users WHERE user_name = :query OR (user_firstname = :query OR user_lastname = :query) ORDER BY user_name ASC', [':query' => $query]);
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
            <?php } ?>
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
</head>
<body>
   <h1>Search</h1>
   <form id="search-form">
      <input type="text" placeholder="Search" id="search-input">
      <button type="submit">-></button>
   </form>
   <div id="results"></div>
   <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
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
</body>
</html>
