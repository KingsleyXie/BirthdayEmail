<?php
namespace Tool;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class BirthdayEmail
{
    private $mail;

    public function __construct() {
        $conf = Config::$mail;

        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();

        $this->mail->Host = $conf['host'];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $conf['username'];
        $this->mail->Password = $conf['password'];
        $this->mail->SMTPSecure = $conf['secure'];
        $this->mail->Port = $conf['port'];

        $this->mail->CharSet = 'UTF-8';
        $this->mail->setFrom($conf['address'], $conf['name']);
    }

    public function sendBirthdayCard($addr, $card, $line) {
        $conf = Config::$mail['card'];
        try {
            $this->mail->addAddress($addr);

            $this->mail->Subject = $conf['subject'];
            $this->mail->addEmbeddedImage($card, 'card'); // Use `cid:card` as value of src in HTML code
            $this->mail->addEmbeddedImage($line, 'line'); // Use `cid:line` as value of src in HTML code

            $this->mail->Body = $conf['body'];
            $this->mail->AltBody = $conf['altbody'];
            $this->mail->isHTML(true);

            $this->mail->send();
        } catch (Exception $e) {
            throw new \Exception($this->mail->ErrorInfo);
        }
    }

    public function sendErrorMsg($msg) {
        $conf = Config::$mail['error'];
        foreach ($conf['admin'] as $addr) {
            try {
                $this->mail->addAddress($addr);
                $this->mail->Subject = $conf['subject'];
                $this->mail->Body = $msg;
                $this->mail->send();
            } catch (Exception $e) {
                throw new \Exception($this->mail->ErrorInfo);
            }
        }
    }

    public function sendRemind($ministers, $subject, $msg) {
        foreach ($ministers as $minister) {
            try {
                $this->mail->addAddress($minister['email']);
                $this->mail->Subject = $subject;
                $this->mail->Body = $msg;
                $this->mail->send();
            } catch (Exception $e) {
                throw new \Exception($this->mail->ErrorInfo);
            }
        }
    }
}
