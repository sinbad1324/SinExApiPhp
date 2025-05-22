<?php

namespace Mailer;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class Mailer
{
    /**
     * Cette function envoie un mail en html css
     * @param string $to le destinataire
     * @param string $name le nom du destinataire
     * @param string $subject sujet
     * @param string $html message
     * @return bool
     */
    public  static function SendHtml($to, $name, $subject, $html): bool
    {
        $mail = static::Connection();
        $mail->addAddress($to, $name);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $html;
        return $mail->send();
    }
    /**
     * Cette function envoie un mail avec un message simple
     * @param string $to le destinataire
     * @param string $name le nom du destinataire
     * @param string $subject sujet
     * @param string $message message
     * @return bool
     */
    public static function Send($to, $name, $subject, $message): bool
    {
        $mail = static::Connection();
        $mail->addAddress($to, $name);
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $message;
        return $mail->send();
    }

    /**
     * Cette function se connect a la passe de
     * @return PHPMailer
     */
    private static function Connection(): PHPMailer
    {
        define("NAME", "SinDev");
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL'];
        $mail->Password = $_ENV["APP_PASS"];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        $mail->setFrom($_ENV['MAIL'], NAME);
        return $mail;
    }

    public static function SendMe($from, $name, $message): bool
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL'];
        $mail->Password = $_ENV["APP_PASS"];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        $mail->setFrom($from, $name);
        $mail->addAddress("mohammad.izdpn@eduge.ch", $name);
        $mail->isHTML(false);
        $mail->Subject = "ContactFromPortfolio";
        $mail->Body = $message;
        return $mail->send();
    }

}
