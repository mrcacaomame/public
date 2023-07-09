<?php
    namespace App\Models\Account;

    use App\Models\Mysql\Mysql;
    use App\Models\Mysql\MysqlException;

    use Log;

    const DB_NAME = "demo_app";
    function Signout (string $user_id) : bool{
        try{
            $info = new Mysql ();
            $info->autoLoad ();
            $info->connect (DB_NAME);

            $sql = "UPDATE `users` SET `del`=1 WHERE `id`=$user_id LIMIT 1;";
            if (!$info->execute ($sql)){
                return false;
            }
            return true;
        }catch (MysqlException $e){
            Log::info ($e);
        }
        return false;
    }
?>