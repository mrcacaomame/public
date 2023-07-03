<?php
    class Error{
        private static function getCurrentTime () : string{
            $microtime = microtime(true);
            $microseconds = sprintf("%06d", ($microtime - floor($microtime)) * 1000000);
            $date = new DateTime(date('Y-m-d H:i:s.' . $microseconds, $microtime));
            return $date->format('Y/m/d-H:i:s.u');
        }
        public static function log ($file_path, $msg) : bool {
            $t = "[".Error::getCurrentTime ()."]>>";
            if (file_put_contents ($file_path, $t.$msg, FILE_APPEND | LOCK_EX) === false){
                return false;
            }
            return true;
        }
    }
?>