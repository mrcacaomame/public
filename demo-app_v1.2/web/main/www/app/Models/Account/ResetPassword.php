<?php
    namespace App\Models\Account;

    use App\Models\Mysql\Mysql;
    use App\Models\Mysql\MysqlException;

    use Log;
    const DB_NAME = "demo_app";

    class ResetPassword{
        public static function exec (string $name, string $email, string $password) : bool{
            try{
                $info = new Mysql ();
                $info->autoLoad ();
                $info->connect (DB_NAME);

                $hash = hash ("sha512", $password);
                $bcrypt = password_hash ($hash, PASSWORD_BCRYPT);

                $sql = "UPDATE `users` SET `password`='$bcrypt' WHERE `name`='$name' && `email`='$email' && `del`=0 LIMIT 1;";
                if (!$info->execute ($sql)){
                    return false;
                }
                return true;
            }catch (MysqlException $e){
                Log::info ($e->getMessage());
            }
            return false;
        }
    }
?>