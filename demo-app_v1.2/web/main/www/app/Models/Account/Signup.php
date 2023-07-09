<?php
    namespace App\Models\Account;
    use App\Models\EMail\EMail;
    use App\Models\EMail\EMailException;
    use App\Models\Mysql\Mysql;
    use App\Models\Mysql\MysqlException;
    use App\Models\Session\Session;

    use Log;
    class Signup{
        private const EMAIL_FROM = "humanyokweb@gmail.com";
        private const DB_NAME = "demo_app";
        private static function generateToken (int $len) : string{
            $token = "";
            $char = "0123456789";
            $char_len = strlen ($char);
            for ($j = 0; $j < $len; $j ++){
                $rand = mt_rand (0, $char_len - 1);
                $token .= $char[$rand];
            }
            return $token;
        }
        private static function getTime (string | null $interval = null) : string{
            $currentDateTime = new \DateTime();
            if ($interval !== null){
                $currentDateTime->add(new \DateInterval($interval));
            }
            return $currentDateTime->format('Y-m-d H:i:s');
        }
        public static function setInfo (string $username, string $email, string $password) : bool{
            try{
                Log::info ("path: setInfo");
                $info = new Mysql ();
                $info->autoLoad ();
                $info->connect (Signup::DB_NAME);
                
                $hash = hash ("sha512", $password);
                $hash = password_hash ($hash, PASSWORD_BCRYPT);

                $sql = "INSERT INTO `informations` (`height`, `weight`) VALUES (0.0, 0.0);";
                if (!$info->execute ($sql)){
                    Log::info ("$sql");
                    return false;
                }

                $last_id = $info->lastID ();

                $sql = "INSERT INTO `users` (`name`, `email`, `password`, `informations`) VALUES ('$username', '$email', '$hash', $last_id);";
                if (!$info->execute ($sql)){
                    Log::info ("$sql");
                    return false;
                }
                return true;
            }catch (MysqlException $e){
                Log::info ($e->getMessage ());
            }
            return false;
        }
        public static function sendEmail (string $email) : bool{
            try{
                if (!EMail::validateEmail ($email)){
                    
                    throw new EMailException ("EMail exception: Non-mail values were posted.");
                }

                $info = new Mysql ();
                $info->autoLoad ();
                $info->connect (Signup::DB_NAME);

                $sql = "SELECT COUNT(`id`) FROM `users`  WHERE `email`='$email' && `del`=false LIMIT 1;";
                $query = $info->executeQuery ($sql);
                if (empty ($query)){
                    throw new MysqlException ("Mysql exception: `$sql` not execute.");
                }

                foreach ($query as $row){
                    if ($row[0] >= 1){
                        // It already contains a value.
                        return false;
                    }
                    break;
                }

                // Create the Token
                $token = Signup::generateToken (6);

                $tm = Signup::getTime ("PT10M");
                $sql = "INSERT INTO `onetime_tokens` (`token`, `email`, `break_time`) VALUES ('$token', '$email', '$tm');";
                if (!$info->execute ($sql)){
                    // Unset the token.
                    throw new MysqlException ("Mysql exception: Unset the token.");
                }

                $last_id = $info->lastID ();
                
                Log::info ($last_id);
                Session::set ("onetime_id", $last_id);
                Session::set ("email", $email);

                $em = new EMail ();
                $em->setFrom (Signup::EMAIL_FROM);
                $em->setTo ($email);
                $em->setHeader ("demo-app");
                $em->setText ($token);
                $em->send ();
                return true;
            }catch (EMailException $e){
                // Error
                throw $e;
            }catch (MysqlException $e){
                // Error
                throw $e;
            }
        }
    }
?>