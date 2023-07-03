<?php
    namespace App\Models;
    class Account{
        public static function token ($len = 6) : string {
            $bytes = random_bytes (3);
            $token = bin2hex ($bytes);
            return substr ($token, 0, $len);
        }
    }
?>