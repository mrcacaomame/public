<?php
    namespace App\Models\Account;
    
    use App\Models\Mysql\Mysql;
    use App\Models\Mysql\MysqlException;
    use Log;

    class OnetimePass{
        private const DB_NAME = "demo_app";
        public static function check (string $id, string $email, string $token) : bool{
            try{
                $db_token = null;
                $break_time = new \DateTime ();
                $info = new Mysql ();
                $info->autoLoad ();
                $info->connect (OnetimePass::DB_NAME);

                $sql = "SELECT `token`,`break_time` FROM `onetime_tokens` WHERE `id`='$id' && `email`='$email' &&`del`=false LIMIT 1;";
                $query = $info->executeQuery ($sql);
                Log::info ($sql);
                if (empty ($query)){
                    // Unset query.
                    Log::info ($sql);
                    return false;
                }

                foreach ($query as $row){
                    $db_token = $row[0];
                    $break_time = new \DateTime ($row[1]);
                    break;
                }

                if ($db_token === null){
                    Log::info ("db_token == null or break_time == null");
                    return false;
                }

                $nt = new \DateTime ();
            $ret = (($token === $db_token)/* && ($nt >= $break_time)*/);
                if ($ret){
                    $sql = "UPDATE `onetime_tokens` SET `del`=true WHERE `id`=$id;";
                    Log::info ($sql);
                    $info->execute ($sql);
                }
                return $ret;
            }catch (MysqlException $e){
                // error
                Log::info ($e->getMessage());
            }
            return false;
        }
    }
?>