<?php
    namespace App\Http\Controllers;

    use App\Models\Session\Session;
    use App\Models\Account\Signin;
    use App\Models\Account\ResetPassword;

    use Log;

    class ResetPasswordController extends Controller{
        private function loginCheck () : bool{
            $user_id = Session::get("user_id");
            $user_token = Session::get ("user_token");
            if ($user_id === false || $user_token === false){
                return false;
            }
            return Signin::token ($user_id, $user_token);
        }
        public function get (){
            if (!$this->loginCheck ()){
                return redirect ()->to ("account");   
            }
            return view ("account.reset_password");
        }
        public function post (){
            if (!(isset ($_POST["name"]) && isset ($_POST["email"]) && isset ($_POST["original_password"]) && isset ($_POST["new_password"]))){
                return redirect ()->to ("reset-password");
            }

            $name     = $_POST["name"];
            $email    = $_POST["email"];
            $ori_pass = $_POST["original_password"];
            $new_pass = $_POST["new_password"];

            Log::info ("name: $name, email: $email, ori_pass: $ori_pass, new_pass: $new_pass");
            if (!Signin::password ($name, $email, $ori_pass)){
                return redirect ()->to ("reset-password");
            }
            if (!ResetPassword::exec ($name, $email, $new_pass)){
                return redirect ()->to ("reset-password");
            }
            Session::set ("user_token", "");
            return redirect ()->to ("account");
        }
    }
?>