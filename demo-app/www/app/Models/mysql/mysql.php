<?php

use Illuminate\Support\Facades\DB;

class Mysql
{
    private const ERROR_LOG_PATH = "";
    public static function setToken($token): bool
    {
        try {
            $res = DB::table('users')->update(['token' => $token]);
            return $res ? true : false;
        } catch (\Exception $e) {
            if (!Error::log (Mysql::ERROR_LOG_PATH, $e)){
                // エラーをうまくログに書き込むことができませんでした。
                
            }
            return false;
        }
    }

    public static function getUserPassword($email): ?string
    {
        try {
            $res = DB::table('users')->select('password')->where('email', $email)->limit(1)->get();
            return $res->isEmpty() ? null : $res[0]->password;
        } catch (\Exception $e) {
            
            return null;
        }
    }
}
