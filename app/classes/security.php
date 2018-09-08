<?php

class Security {
   public static function e($value) {
      $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8')
      return $value;
   }
}
