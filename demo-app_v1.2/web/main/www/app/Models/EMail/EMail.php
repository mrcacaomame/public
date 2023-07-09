<?php
namespace App\Models\EMail;

// require_once ("vendor/autoload.php");
use PHPMailer\PHPMailer\PHPMailer;
use App\Models\Mysql\MysqlException;
use App\Models\EMail\EMailException;

class EMail {
    private const CONFIG_FILE = "/var/config/email.json";
    private const ERROR_LOG_PATH = "/var/log/email/error.log";
    private $HOST;
    private $USERNAME;
    private $PASSWORD;
    private $FROM;
    private $TO;
    private $HEADER;
    private $TEXT;

    private static function getConfig($json, string | null &$HOST, string | null &$USERNAME, string | null &$PASSWORD): void {
        if (!(isset($json->KEY) && isset($json->IV) && isset($json->TAG))) {
            // 指定されたjsonのキーが含まれていませんでした
            $msg = "EMail exception: Syntax error in " . EMail::CONFIG_FILE . ".";
            // error_log($msg, 3, EMail::ERROR_LOG_PATH);
            throw new EMailException($msg);
        }

        $host  = getenv("EMAIL_HOST");
        $username = getenv("EMAIL_USERNAME");
        $password = getenv ("EMAIL_PASSWORD");
        

        if ($host === false || $username === false || $password === false){
            // host と username と password の環境変数が設定されていません
            $msg = "Mysql exception: Unset host, username and password.";
            throw new MysqlException ($msg);
        }
        $key = $json->KEY;
        $iv = $json->IV;
        $tag = $json->TAG;

        exec ("aes256_gcm_decrypt ".$password." ".$key. " ".$iv." ".$tag, $output, $return_code);

        if ($return_code == -1){
            // 暗号化の解除に失敗しました
            $msg = "Mysql exception: Encryption decryption failed.";
            // Error::log ($this::ERROR_LOG_PATH, $msg);
            throw new MysqlException ($msg);
        }

        $HOST = $host;
        $USERNAME = $username;
        $PASSWORD = $output[0];
    }

    public function __construct() {
        $m_conf = file_get_contents(EMail::CONFIG_FILE);
        if ($m_conf === false) {
            // コンフィグファイルの設定が行われていません
            $msg = "EMail exception: Doesn't open the file " . EMail::CONFIG_FILE . ".";
            error_log($msg, 3, EMail::ERROR_LOG_PATH);
            throw new EMailException($msg);
        }

        $json = json_decode($m_conf);
        echo $m_conf;
        if ($json === null && json_last_error() !== JSON_ERROR_NONE) {
            // JSONとして読み込むことができませんでした
            $msg = "EMail exception: Doesn't create the json data.";
            error_log($msg, 3, EMail::ERROR_LOG_PATH);
            throw new EMailException($msg);
        }

        try {
            EMail::getConfig($json, $this->HOST, $this->USERNAME, $this->PASSWORD);
        } catch (EMailException $e) {
            throw $e;
        }
    }

    public function setFrom(string $from): void {
        $this->FROM = $from;
    }

    public function setTo(string $to): void {
        $this->TO = $to;
    }

    public function setHeader(string $header): void {
        $this->HEADER = $header;
    }

    public function setText(string $text): void {
        $this->TEXT = $text;
    }

    public function send(): void {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $this->HOST;
        $mail->SMTPAuth = true;
        $mail->Username = $this->USERNAME;
        $mail->Password = $this->PASSWORD;
        $mail->Port = 587;
        $mail->setFrom($this->FROM);
        $mail->addAddress($this->TO);
        $mail->Subject = $this->HEADER;
        $mail->Body = $this->TEXT;
        if (!$mail->send()) {
            $msg = "EMail exception: Failed to send email. Error: " . $mail->ErrorInfo;
            // error_log($msg, 3, EMail::ERROR_LOG_PATH);
            throw new EMailException($msg);
        }
    }
    public static function validateEmail ($email) : bool{
        $pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        if (preg_match ($pattern, $email)){
            return true;
        }
        return false;
    }
}
?>
