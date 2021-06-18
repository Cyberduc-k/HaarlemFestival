<?php
require_once __DIR__.'/../libs/TCPDF/config/tcpdf_config.php';
require_once __DIR__.'/../libs/TCPDF/tcpdf.php';

class MailService {
    private static $instance = null;

    private const FROM = "assignment@bramsierhuis.nl";
    private const HOST = "send.one.com";
    private const PASSWORD = "PHPAssignment";
    private const PORT = 465;
    private const FROM_NAME = "Haarlem Festival";

    private function __construct() {}

    // Singleton
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new MailService();
        }

        return self::$instance;
    }

    public function sendMailWithInvoice(String $to, String $subject, String $body, TCPDF $ticketPdf, TCPDF $invoicePdf): bool{
        require_once(__DIR__."/../libs/PHPMailer/PHPMailerAutoload.php");
        $tickets = $ticketPdf->Output('', 'S');
        $invoice = $invoicePdf->Output('', 'S');

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPSecure = "ssl";
        $mail->Host = self::HOST;
        $mail->SMTPAuth = true;
        $mail->Username = self::FROM;
        $mail->Password = self::PASSWORD;
        $mail->Port = self::PORT;
        $mail->IsHTML(false);
        $mail->From = self::FROM;
        $mail->FromName = self::FROM_NAME;
        $mail->Sender = self::FROM;
        $mail->Body = $body;
        $mail->Subject = $subject;
        $mail->addAddress($to);
        $mail->addStringAttachment($tickets, 'tickets.pdf');
        $mail->addStringAttachment($invoice, 'invoice.pdf');

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

    // Send an email using the PHPMailer library: https://github.com/PHPMailer/PHPMailer
    public function sendMail(String $to, String $subject, String $body): bool {
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
