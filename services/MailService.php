<?php

class MailService
{
    private static $instance = null;

    private const FROM = "assignment@bramsierhuis.nl";
    private const HOST = "send.one.com";
    private const PASSWORD = "PHPAssignment";
    private const PORT = 465;
    private const FROM_NAME = "Bram Bierhuis";

    private function __construct()
    {
    }

    //Singleton
    public static function getInstance()
    {
        if (self::$instance == null)
        {
            self::$instance = new MailService();
        }

        return self::$instance;
    }

    //Send an email using the PHPMailer library: https://github.com/PHPMailer/PHPMailer
    public function sendMail(String $to, String $subject, String $body): bool{
        require_once(__DIR__."/../libs/PHPMailer/PHPMailerAutoload.php");
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPSecure = "ssl";
        $mail->Host = self::HOST;
        $mail->SMTPAuth = true;
        $mail->Username = self::FROM;
        $mail->Password = self::PASSWORD;
        $mail->Port = self::PORT;
        $mail->IsHTML(true);
        $mail->From = self::FROM;
        $mail->FromName = self::FROM_NAME;
        $mail->Sender = self::FROM;
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->addAddress($to);

        try {
            if ($mail->Send()) {
                return true;
            } else {
                echo "Mailer ErrorLog: " . $mail->ErrorInfo;
                return false;
            }
        } catch (phpmailerException $e) {
            echo "Mailer ErrorLog: ".$e;
            return false;
        }
    }
}
?>