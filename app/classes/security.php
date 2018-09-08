<?php

class Security {
   public static function check($value) {
      return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
   }
}
