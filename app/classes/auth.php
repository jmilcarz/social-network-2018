<?php

class Auth {
   public static $system_cookie_name = 'HONET';

   public function logout() {
      DB::query('DELETE FROM login_tokens WHERE logint_userid=:userid', array(':userid'=>self::loggedin()));
      setcookie("" . self::$system_cookie_name . "", '1', time()-3600);
      setcookie("" . self::$system_cookie_name . "_", '1', time()-3600);
      header('Location: index.php');
      exit();
   }

   public static function loggedin() {
      if (isset($_COOKIE['' . self::$system_cookie_name . ''])) {
         if (DB::query('SELECT logint_userid FROM login_tokens WHERE logint_token=:token', [':token'=>sha1($_COOKIE['' . self::$system_cookie_name . ''])])) {
            $userid = DB::query('SELECT logint_userid FROM login_tokens WHERE logint_token=:token', [':token'=>sha1($_COOKIE['' . self::$system_cookie_name . ''])])[0]['logint_userid'];
            if (isset($_COOKIE['' . self::$system_cookie_name . '_'])) {
               return $userid;
            } else {
               $cstrong = true;
               $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
               DB::query('INSERT INTO login_tokens VALUES (\'\', :token, :user_id)', [':token'=>sha1($token), ':user_id'=>$userid]);
               ## echo ';
               DB::query('DELETE FROM login_tokens WHERE logint_token=:token', [':token'=>sha1($_COOKIE["" . self::$system_cookie_name . ""])]);
               setcookie("" . self::$system_cookie_name . "", $token, time() + 60 * 60 * 24 * 30, '/', NULL, NULL, TRUE);
               setcookie("" . self::$system_cookie_name . "_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);
               return $userid;
            }
         }
      }
      return false;
   }

   public function guard() {
      if (!self::loggedin()) {
         // require_once("../app/modules/guard-error.html");
         // TODO: guard error page include here
         exit();
      }
   }

   public function login($login, $pass) {
      header('Location: login.php?error=5');
      if (strpos($login, '@') == true) {
         header('Location: login.php?error=5');
         if (DB::query('SELECT user_email FROM users WHERE user_email=:email', [':email'=>$login])[0]['user_email']) {
            if (password_verify($pass, DB::query('SELECT user_password FROM users WHERE user_email=:email', [':email'=>$login])[0]['user_password'])) {
               $cstrong = true;
               $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
               $user_id = DB::query('SELECT id FROM users WHERE user_email=:email', [':email'=>$login])[0]['id'];
               DB::query('INSERT INTO login_tokens VALUES (\'\', :token, :user_id)', [':token'=>sha1($token), ':user_id'=>$user_id]);
               # '
               setcookie("" . self::$system_cookie_name . "", $token, time() + 60 * 60 * 24 * 30, '/', NULL, NULL, TRUE);
               setcookie("" . self::$system_cookie_name . "_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);
               header("Location: index.php");
               exit();
            }else {header('Location: login.php?error=1'); exit();}
         }else {header('Location: login.php?error=2'); exit();}
      }else {
         header('Location: login.php?error=5');
         if (DB::query('SELECT user_username FROM users WHERE user_username=:username', [':username'=>$login])[0]['user_username']) {
            if (password_verify($pass, DB::query('SELECT user_password FROM users WHERE user_username=:username', [':username'=>$login])[0]['user_password'])) {
               $cstrong = true;
               $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
               $user_id = DB::query('SELECT id FROM users WHERE user_username=:username', [':username'=>$login])[0]['id'];
               DB::query('INSERT INTO login_tokens VALUES (\'\', :token, :user_id)', [':token'=>sha1($token), ':user_id'=>$user_id]);
               # '
               setcookie("" . self::$system_cookie_name . "", $token, time() + 60 * 60 * 24 * 30, '/', NULL, NULL, TRUE);
               setcookie("" . self::$system_cookie_name . "_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);
               header("Location: index.php");
               exit();
            }else {header('Location: login.php?error=1'); exit();}
         }else {header('Location: login.php?error=2'); exit();}
      }
   }
}
