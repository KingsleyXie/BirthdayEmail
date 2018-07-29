<?php
require_once 'FontLib/Autoloader.php';

require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';
require_once 'PHPMailer/Exception.php';

require_once 'Text/FontChecker.php';
require_once 'Text/TextSizeParser.php';
require_once 'Text/TextBox.php';
require_once 'Text/Name.php';

require_once 'Tool/Config.php';
require_once 'Tool/BirthdayData.php';
require_once 'Tool/Logger.php';
require_once 'Tool/Email.php';

use Text\FontChecker;
use Text\TextSizeParser;
use Text\TextBox;
use Text\Name;

use Tool\BirthdayData;
use Tool\Logger;
use Tool\Email;

class SendBirthdayEmails
{
    public function __construct() {
        // Set the timezone for date and email functions
        date_default_timezone_set('Asia/Shanghai');
    }

    public function run() {
        $data = BirthdayData::get(); $num = count($data);
        $day = date('m-d'); $time = date(DATE_RFC2822);

        Logger::append('data', "\n[$time]\t$day ($num)");
        foreach($data as $a) {
            Logger::append('data', implode(', ', $a));
        }

        $this->sendEmails($data, $day);
    }

    public function generateImage($text, $filename, $withRef =false) {
        $image = imagecreatefrompng('Assets/card.png');
        $color = imagecolorallocate($image, 144, 139, 134);

        $asset_path = getcwd() . '/Assets';
        $font_path = $asset_path . '/name.ttf';
        $font_path_alternate = $asset_path . '/alt.ttf';

        $checker = new FontChecker;
        $parser = new TextSizeParser;
        $name = new Name($text);

        $font_size = 0;
        if ($checker->isStringValid($name->clear, $font_path)) {
            $font_size = $parser->getSize($name->origin);
        } else {
            // If current font does not support the name
            // Change it to another widely-supported font
            $font_path = $font_path_alternate;
            $font_size = $parser->getAltSize($name->origin);
        }

        // Get text bounding and render it on the image
        $bounding = TextBox::getBounding($font_size, $font_path, $name->render);
        imagettftext(
            $image, $font_size, 0,
            $bounding['baseline_x'],
            $bounding['baseline_y'],
            $color, $font_path, $name->render
        );

        // Draw reference lines if requested
        if ($withRef) {
            // Outer Rectangle
            imagerectangle(
                $image,
                TextBox::$RECT['left'],
                TextBox::$RECT['top'],
                TextBox::$RECT['right'],
                TextBox::$RECT['bottom'],
                $color
            );

            // Inner Rectangle
            imagerectangle(
                $image,
                $bounding['upper_left_x'],
                $bounding['upper_left_y'],
                $bounding['lower_right_x'],
                $bounding['lower_right_y'],
                $color
            );

            // Text Height Lines
            imageline(
                $image,
                000, $bounding['upper_left_y'],
                950, $bounding['upper_left_y'],
                $color
            );
            imageline(
                $image,
                000, $bounding['lower_right_y'],
                950, $bounding['lower_right_y'],
                $color
            );
        }

        // Save as PNG file and free the corresponding memory
        imagepng($image, $filename);
        imagedestroy($image);
    }

    public function sendEmails($data, $day) {
        $dir = "images/$day/";
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        foreach ($data as $one) {
            $mail = new Email;
            try {
                $name = $one['receiver']; $addr = $one['email'];

                $filename = "$dir/Happy-Birthday-To-$name.png";
                $this->generateImage($name, $filename);

                $mail->sendBirthdayCard($addr, $filename, 'Assets/line.png');
                Logger::append('mail', "[$day] $name<$addr>");
            } catch (Exception $err) {
                $msg = "\n[$day] $name<$addr>\n" . $err;

                Logger::append('fail', $msg);
                $mail->sendErrorMsg($msg);
            }
        }
    }
}
