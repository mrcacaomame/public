<?php

namespace App\Http\Controllers;
namespace App\Models;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    // サインイン画面
    public function get (){
        $opt = "signin";
        if (isset ($_GET["opt"])){
            $opt = $_GET["opt"];
        }
        switch ($opt){
            case ("signin"):
                return view ("account.signin");
            case ("signup"):
                return view ("account.signup");
                
        }
    }
    // 
    public function post (){
        if (!(isset ($_POST["email"]) && isset ($_POST["password"]) && isset ($_POST["opt"]))){
            return view ("account.signin");
        }
        $opt = $_POST["opt"];

        switch ($opt){
            case ("signin"):
                // 認証作業
                

                break;
            case ("signup"):
                // トークンの発行
                $token = Account::token(6);
                // トークンの先頭が$0ならワンタイムパスのトークン$1ならログインのためのトークン
                $token = "$0".$token;

                
                break;
            case ("token"):
                // トークンを確認する

                break;
            default:
                return view ("account.signin");
        }
    }
}
