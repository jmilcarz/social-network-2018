<?php require('app/autoload.php');

if (!Auth::loggedin()) {
   ?>

   <!DOCTYPE html>
   <html lang="en">
   <head>
      <?php require('app/incs/head-metas.inc.php'); ?>
      <title>Social Network</title>
   </head>
   <body>
      <h1>Home Page</h1>
      <a href="register.php">register</a><br>
      <div id="errors">
         <?php
         if (isset($_GET['error'])) {
            $error = Security::check($_GET['error']);
            echo $error;
         }
         ?>
      </div>
      <form action="login.php" method="post">
         <input type="text" name="login" placeholder="username or email address">
         <input type="password" name="password" placeholder="password">
         <button type="submit" name="loginbtn">login</button>
      </form>
   </body>
   </html>

   <?php
   exit(); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <?php require('app/incs/head-metas.inc.php'); ?>
   <title>Social Network</title>
   <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
</head>
<body>
   <form action="" method="post">
      <button name="logoutbtn" type="submit">logout</button>
   </form>
   <hr>
   <div id="user_create_new_post_container">
      <form method="post" id="user_create_new_post_form">
         <style>
            #user_create_new_post_textarea { width: 550px; min-width: 550px; min-height: 150px; height: 150px; max-height: 450px; outline: none; border: 2px solid rgb(231, 155, 3); overflow: hidden; overflow-y: scroll; background: #fff; resize: vertical; display: block; margin: 0 auto; font-size: 17px; padding: 10px; box-sizing: border-box; }
         </style>
         <textarea name="user_create_new_post_textarea" id="user_create_new_post_textarea" cols="50" rows="5"></textarea>
         <select name="user_create_new_post_privacy" id="user_create_new_post_privacy">
            <option value="public">public</option>
            <option value="friends">friends only</option>
            <option value="private">private</option>
         </select>
         <button id="user_create_new_post_submit" name="user_create_new_post_submit" type="submit">post</button>
      </form>
      <script>
         $(function() {
            $("#user_create_new_post_form").submit(function(e) {
               e.preventDefault();
               let content = $("#user_create_new_post_textarea").val();
               let userid = <?php echo Auth::loggedin(); ?>;
               let privacy = $("#user_create_new_post_privacy").val();
               $.ajax({
                  url: "<?php echo App::$APP_DIR; ?>app/api/posts.php",
                  type: 'POST',
                  data: { APIload: true, NEWPOSTuserid: userid, NEWPOSTcontent: content, NEWPOSTprivacy: privacy},
                  success: function(data) {
                     $('#user_create_new_post_container').html(data);
                  }
               });
            });
         });
      </script>
   </div>
   <div class="posts">
      <?php
         echo Auth::loggedin();
         $posts = DB::query('SELECT posts.posts_id, posts.posts_content, posts.posts_tags, posts.posts_likes, posts.posts_comments, posts.posts_timestamp, users.id, users.user_name, users.user_username, users.user_sex, users.user_avatar FROM posts, users, followers WHERE users.id = :userid AND posts.posts_privacy = 1 AND followers.followers_userid = :userid AND posts.posts_authorid = followers.followers_followerid', [':userid' => Auth::loggedin()]);
         foreach ($posts as $post) { ?>
            <div style='width: 540px; background: #efefef; margin-bottom: 15px;'>
               <div3>
                  <img src="<?php echo $post['user_avatar']; ?>">
                  <h1><?php echo $post['user_name']; ?></h1>
                  <p style='text-align: right;'><?php echo $post['posts_timestamp']; ?></p>
               </div>
               <div>
                  <?php echo $post['posts_content']; ?>
               </div>
            </div>
         <?php }
      ?>
   </div>
</body>
</html>
