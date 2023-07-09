<?php
    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\Account\Signin;
    use App\Models\Account\Signup;
    use App\Models\Account\OnetimePass;
    use App\Models\Session\Session;

    use Log;

    class AccountController extends Controller{
        private function loginCheck () : bool{
            $user_id = Session::get("user_id");
            $user_token = Session::get ("user_token");
            if ($user_id === false || $user_token === false){
                return false;
            }
            return Signin::token ($user_id, $user_token);
        }
        public function get (Request $req){
            if ($this->loginCheck ()) {
                return redirect ()->to ("information");
            }
            // Base a screen selector
            $opt = $this::setOpt ();
            switch ($opt){
                case ("signin"):
                    return view ("account.signin");
                case ("signup"):
                    return view ("account.signup");
                case ("onetimepass"):
                    return view ("account.onetimepassword");
            }
            return view ("account.signin");
        }
        public function post (Request $req){
            if ($this->loginCheck ()) {
                return redirect ()->to ("information");
            }
            try{
                $opt = $this::setOptPost ();
                switch ($opt){
                    case ("signin"):
                        if (!(isset ($_POST["name"]) && isset ($_POST["email"]) && isset ($_POST["password"]))){
                            return redirect ()->to ("account");
                        } 
                        $name = $_POST["name"];
                        $email = $_POST["email"];
                        $password = $_POST["password"];
                        Log::info ("name: $name, email: $email, password: $password");
                        if (Signin::password ($name, $email, $password)){
                            Log::info ("true");
                        }
                        return redirect ()->to ("account");
                    case ("signup"):
                        Log::info ("signup");
                        if (!isset ($_POST["email"])){
                            // Unset email
                            Log::info ("!isset (email)");
                            return redirect ()->to ("account");
                        }
                        
                        if (!Signup::sendEmail ($_POST["email"])){
                            Log::info ("Unsend to email");
                            return redirect ()->to ("account?opt=signup");
                        }
                        Log::info ("account?opt=onetimepass");
                        return redirect ()->to ("account?opt=onetimepass");
                        
                    case ("onetimepass"):
                        if (!isset ($_POST["password"])){
                            return redirect ()->to ("account");
                        }

                        $token_id = Session::get("onetime_id");
                        $email = Session::get ("email");
                        if ($token_id === false || $email === false){
                            return redirect ()->to ("account");
                        }

                        if (!OnetimePass::check ($token_id, $email, $_POST["password"])){
                            Log::info ("token:".$token_id.",email:".$email);
                            return redirect ()->to ("account?opt=onetimepass");
                        }

                        return view ("account.set_info");
                    
                    case ("set_info"):
                        if (!(isset ($_POST["name"])) && (isset ($_POST["password"]))){
                            return redirect ()->to ("account");
                        }
                        $name     = $_POST["name"];
                        $password = $_POST["password"];
                        $email    = Session::get ("email");
                        if (!Signup::setInfo ($name, $email, $password)){
                            Log::info ("Unset");
                            return redirect ()->to ("account");
                        }
                        return redirect ()->to ("account");
                }
                return view ("account.signin");
            }catch (\Exception $e){
                Log::info ($e->getMessage());
            }
            return redirect ()->to ("account?opt=signup");
        }
        private static function setOpt () : string{
            $opt = "signin";
            if (isset ($_GET["opt"])){
                $opt = $_GET["opt"];
            }
            return $opt;
        }
        private static function setOptPost () : string{
            $opt = "signin";
            if (isset ($_POST["opt"])){
                $opt = $_POST["opt"];
            }
            return $opt;
        }
    }
?>