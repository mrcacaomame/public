<?php
    namespace App\Models\Mysql;

    class Mysql{
        private const CONFIG_FILE = "/var/config/mysql.json";
        private \PDO $pdo;
        private string | null $HOST;
        private string | null $PORT;
        private string | null $CHAR;
        private string | null $USERNAME;
        private string | null $PASSWORD;
        public function __construct (string | null $host = null, string | null $port = "3306", string | null $char = "utf8", string | null $username = null, string | null $password = null){
            $this->HOST     = $host;
            $this->PORT     = $port;
            $this->CHAR     = $char;
            $this->USERNAME = $username;
            $this->PASSWORD = $password;
        }
        public function autoLoad () : void{
            $path = $this::CONFIG_FILE;
            if ($path === false){
                $msg = "Mysql exception: Unset the config path.";
                throw new MysqlException ($msg);
            }
            if (!file_exists ($path)){
                $msg = "Mysql exception: Doesn't open the file ".$path.".";
                throw new MysqlException ($msg);
            }

            $conf = file_get_contents ($path);
            $json = json_decode ($conf);

            if ($json === null || $json === json_last_error ()){
                $msg = "Mysql exception: Syntax error in ".$path.".";
                throw new MysqlException ($msg);
            }

            if (!(isset ($json->KEY) && isset ($json->IV) && isset ($json->TAG))){
                $msg = "Mysql exception: Unset the KEY, IV or TAG.";
                throw new MysqlException ($msg);
            }

            $host        = getenv ("MYSQL_HOST");
            $username    = getenv ("MYSQL_USERNAME");
            $aes_password = getenv ("MYSQL_PASSWORD");
            $key = $json->KEY;
            $iv  = $json->IV;
            $tag = $json->TAG;

            if ($host === false || $username === false || $aes_password === false){
                $msg = "Mysql exception: The environment path is not set.";
                throw new MysqlException ($msg);
            }

            exec ("aes256_gcm_decrypt ".$aes_password." ".$key." ".$iv." ".$tag, $output, $ret);
            if ($ret == -1){
                $msg = "Mysql exception: Encryption decryption failed.";
                throw new MysqlException ($msg);
            }

            $this->HOST     = $host;
            $this->USERNAME = $username;
            $this->PASSWORD = $output[0];
        }
        public function setChar (string $char){
            $this->CHAR = $char;
        }
        public function connect (string $dbname){
            try{
                $dsn = "mysql:host=".$this->HOST.";port=".$this->PORT.";dbname=".$dbname.";charset=".$this->CHAR;
                $this->pdo = new \PDO ($dsn, $this->USERNAME, $this->PASSWORD);
            }catch (\PDOException $e){
                throw new MysqlException ($e->getMessage ());
            }
        }
        public function execute(string $sql): bool{
            try {
                $stmt = $this->pdo->prepare($sql);
                if (!$stmt) {
                    throw new MysqlException("Failed to prepare statement");
                }

                $result = $stmt->execute();
                if (!$result) {
                    throw new MysqlException("Failed to execute query");
                }

                return true;
            } catch (\PDOException $e) {
                throw new MysqlException($e->getMessage());
            }
        }

        public function executeQuery (string $sql) : array{
            try{
                $stmt = $this->pdo->query ($sql);
                return $stmt->fetchAll (\PDO::FETCH_BOTH);
            }catch (\PDOException $e){
                throw new MysqlException ($e->getMessage ());
            }
        }
        public function executeColumn ($sql){
            try{
                $stmt = $this->pdo->query ($sql);
                return $stmt->fetchColumn ();
            }catch (\PDOException $e){
                throw new MysqlException ($e->getMessage ());
            }
        }
        public function lastID () : bool | string{
            return $this->pdo->lastInsertId ();
        }
    }
?>