<?php
namespace Tool;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email
{
    private $conf, $mail;

    public function __construct() {
        $this->conf = Config::$mail;

        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();

        $this->mail->Host = $this->conf['host'];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $this->conf['username'];
        $this->mail->Password = $this->conf['password'];
        $this->mail->SMTPSecure = $this->conf['secure'];
        $this->mail->Port = $this->conf['port'];

        $this->mail->CharSet = 'UTF-8';
        $this->mail->setFrom($this->conf['address'], $this->conf['name']);
    }

    public function sendBirthdayCard($addr, $card, $line) {
        try {
            $this->mail->addAddress($addr);

            $this->mail->Subject = $this->conf['subject'];
            $this->mail->addEmbeddedImage($card, 'card'); // Use `cid:card` as value of src in HTML code
            $this->mail->addEmbeddedImage($line, 'line'); // Use `cid:line` as value of src in HTML code

            $this->mail->Body = $this->conf['body'];
            $this->mail->AltBody = $this->conf['altbody'];
            $this->mail->isHTML(true);

            $this->mail->send();
        } catch (\Exception $e) {
            throw new \Exception($this->mail->ErrorInfo);
        }
    }
}
