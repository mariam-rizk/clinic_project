<?php
namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Core\Logger;

class Mailer
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->setup();
    }

    private function setup()
    {
     
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'c91870041@gmail.com'; 
        $this->mail->Password = 'miur vrqw xpxy cqkc';   
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->Port = 465;

       
        $this->mail->setFrom('c91870041@gmail.com', 'Clinic Contact Form');
        $this->mail->isHTML(true);
    }

    public function sendMail($to, $subject, $body, $replyToEmail = null, $replyToName = null)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($to);
            if ($replyToEmail && $replyToName) {
                $this->mail->addReplyTo($replyToEmail, $replyToName);
            }

            $this->mail->Subject = $subject;
            $this->mail->Body = $body;

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            Logger::Error("Mail Error: " . $this->mail->ErrorInfo);
            return false;
        }
    }
}
