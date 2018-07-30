<?php
namespace Tool;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class BirthdayEmail
{
    private $mailer;

    private function initialize() {
        $conf = Config::$mail;

        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();

        $this->mailer->Host = $conf['host'];
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $conf['username'];
        $this->mailer->Password = $conf['password'];
        $this->mailer->SMTPSecure = $conf['secure'];
        $this->mailer->Port = $conf['port'];

        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->setFrom($conf['address'], $conf['name']);
    }

    public function sendBirthdayCard($addr, $card, $line) {
        $this->initialize();

        $conf = Config::$mail['card'];
        try {
            $this->mailer->addAddress($addr);

            $this->mailer->Subject = $conf['subject'];
            $this->mailer->addEmbeddedImage($card, 'card'); // Use `cid:card` as value of src in HTML code
            $this->mailer->addEmbeddedImage($line, 'line'); // Use `cid:line` as value of src in HTML code

            $this->mailer->Body = $conf['body'];
            $this->mailer->AltBody = $conf['altbody'];
            $this->mailer->isHTML(true);

            $this->mailer->send();
        } catch (Exception $e) {
            throw new \Exception($this->mailer->ErrorInfo);
        }
    }

    public function sendRemind($ministers, $subject, $msg) {
        $this->initialize();

        foreach ($ministers as $minister) {
            try {
                // Former settings should be cleared inside a loop
                $this->mailer->clearAddresses();

                $this->mailer->addAddress($minister['email']);
                $this->mailer->Subject = $subject;
                $this->mailer->Body = $msg;
                $this->mailer->send();
            } catch (Exception $e) {
                throw new \Exception($this->mailer->ErrorInfo);
            }
        }
    }

    public function sendErrorMsg($msg) {
        $this->initialize();

        $conf = Config::$mail['error'];
        foreach ($conf['admin'] as $addr) {
            try {
                // Former settings should be cleared inside a loop
                $this->mailer->clearAddresses();

                $this->mailer->addAddress($addr);
                $this->mailer->Subject = $conf['subject'];
                $this->mailer->Body = $msg;
                $this->mailer->send();
            } catch (Exception $e) {
                // Just do nothing if even the error message can't be sent = =
            }
        }
    }
}
