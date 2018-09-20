<?php

ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);

function __autoload($class_name) {
     require_once('classes/' . $class_name . '.php');
}

if (isset($_POST['logoutbtn'])) {
     Auth::logout();
}
