<?php
    namespace App\Models\Session;
    use Log;
    class Session{
        private static function up () : void{
            if (session_status () === PHP_SESSION_NONE){
                session_start ();
            }
        }
        public static function set (string $key, string $value) : void{
            Session::up ();
            $_SESSION[$key] = $value;
        }
        public static function get (string $key) : bool | string{
            Session::up ();
            if (!isset ($_SESSION[$key])){
                return false;
            }
            return $_SESSION[$key];
        }
    }
?>