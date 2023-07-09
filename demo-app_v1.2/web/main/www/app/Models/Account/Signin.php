<?php
    namespace App\Models\Account;

    use App\Models\Mysql\Mysql;
    use App\Models\Mysql\MysqlException;
    use App\Models\Session\Session;
    use Log;

    class Signin{
        private const DB_NAME = "demo_app";
        public static function token (string $user_id, string $user_token) : bool{
            try{
                $info = new Mysql ();
                $info->autoLoad ();
                $info->connect (Signin::DB_NAME);

                $sql = "SELECT `token` FROM `users` WHERE `id`=$user_id && `del`=false LIMIT 1;";
                $query = $info->executeQuery ($sql);
                if (empty ($query)){
                    return false;
                }

                $hash = "";
                foreach ($query as $row){
                    $hash = $row[0];
                    break;
                }

                $ret = password_verify ($user_token, $hash);
                return $ret;
            }catch (MysqlException $e){
                Log::info ($e->getMessage ());
            }
            return false;
        }
        public static function password (string $name, string $email, string $password){
            try{
                $info = new Mysql ();
                $info->autoLoad ();
                $info->connect (Signin::DB_NAME);

                $sql = "SELECT `id`, `password` FROM `users` WHERE `name`='$name' && `email`='$email' && `del`=false LIMIT 1;";
                $query = $info->executeQuery ($sql);
                
                $pass_hash = "";
                $id = "";
                foreach ($query as $row){
                    $id = $row[0];
                    $pass_hash = $row[1];
                }

                $password = hash ("sha512", $password);
                $ret = password_verify ($password, $pass_hash);
                if ($ret){
                    $token = password_hash (substr ($pass_hash, 20, 50), PASSWORD_BCRYPT);
                    $token = hash ("sha512", $token);

                    $token_hash = password_hash ($token, PASSWORD_BCRYPT);

                    $sql = "UPDATE `users` SET `token`='$token_hash' WHERE `id`=$id LIMIT 1;";
                    if (!$info->execute ($sql)){

                    }
                    Session::set ("user_id", $id);
                    Session::set ("user_token", $token);
                }
                return $ret;
            }catch (MysqlException $e){
                Log::info ($e);
            }
        }
    }
?>