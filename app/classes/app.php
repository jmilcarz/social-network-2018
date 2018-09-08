<?php
require('db.php');

class App extends DB {
   public $serverAdress = ""; # locahost/facebook/...
   public $lang = "";
   public $theme = "";
}

$app = new App;

$app->lang = "en_us";
$app->serverAdress = "/localhost/facebook/";
$app->theme = "light";
