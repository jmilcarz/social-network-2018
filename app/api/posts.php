<?php
require('../autoload.php');
if (isset($_POST['APIload']) && isset($_POST['NEWPOSTuserid']) && isset($_POST['NEWPOSTcontent']) && isset($_POST['NEWPOSTprivacy'])) {
   $userid = Security::check($_POST['NEWPOSTuserid']);
   $content = Security::check($_POST['NEWPOSTcontent']);
   $privacy = Security::check($_POST['NEWPOSTprivacy']);
   $status = Post::createNew($userid, $content, $privacy);

   if ($status == false) {
      exit();
   }
   ?>

   <h1>post published successfuly!</h1>
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
            let userid = <?php echo $userid; ?>;
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
   <?php

}
