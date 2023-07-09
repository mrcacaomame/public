<?php
    namespace App\Models\Information;

    use App\Models\Mysql\Mysql;
    use App\Models\Mysql\MysqlException;

    use Log;
    class Information{
        private const DB_NAME = "demo_app";
        public static function getWeightHeight (string $user_id, string &$weight, string &$height) : bool{
            try{
                $info_id = null;
                $info = new Mysql ();
                $info->autoLoad ();
                $info->connect (Information::DB_NAME);
                
                $sql = "SELECT `informations` FROM `users` WHERE `id`=$user_id && del=0 LIMIT 1;";
                $rows = $info->executeQuery ($sql);
                if (empty ($rows)){
                    return false;
                }
                foreach ($rows as $row){
                    $info_id = $row[0];
                    break;
                }

                $sql = "SELECT `weight`, `height` FROM `informations` WHERE `id`=$info_id && del=0 LIMIT 1;";
                $rows = $info->executeQuery($sql);
                if (empty ($rows)){
                    return false;
                }
                foreach ($rows as $row){
                    $weight = $row[0];
                    $height = $row[1];
                    break;
                }
                return true;
            }catch (MysqlException $e){
                throw $e;
            }
        }
        public static function setWeightHeight (string $user_id, string $weight, string $height) : bool{
            try{
                $info = new Mysql ();
                $info->autoLoad ();
                $info->connect (self::DB_NAME);

                $sql = "UPDATE `informations` SET `weight`=$weight, `height`=$height WHERE `id`=$user_id LIMIT 1;";

                Log::info ($sql);
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