<?php
    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\Account\Signin;

    use App\Models\Information\Information;
    use App\Models\Session\Session;

    use Log;

    class InformationController extends Controller{
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
            try{
                $user_id = Session::get ("user_id");
                $weight = "0.0";
                $height = "0.0";
                if (!Information::getWeightHeight ($user_id, $weight, $height)){
                    return redirect ()->to ("account");
                }
                return view ("information", ["weight"=>$weight, "height"=>$height]);
            }catch (\Exception $e){
                Log::info ($e->getMessage());
            }
        }
        public function post (){
            if (!$this->loginCheck ()){
                return redirect ()->to ("account");
            }
            if (!(isset ($_POST["weight"]) && isset ($_POST["height"]))){
                return redirect ()->to ("information");
            }
            if (!(is_numeric ($_POST["weight"]) && is_numeric ($_POST["height"]))){
                return redirect ()->to ("information");
            }
            $user_id = Session::get ("user_id");
            $weight = $_POST["weight"];
            $height = $_POST["height"];

            if (!Information::setWeightHeight ($user_id, $weight, $height)){

            }
            return redirect ()->to ("information");
        }
    }
?>