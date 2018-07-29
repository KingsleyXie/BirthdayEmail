<?php
require_once 'FontLib\Autoloader.php';

require_once 'Text\FontChecker.php';
require_once 'Text\TextSizeParser.php';
require_once 'Text\TextBox.php';
require_once 'Text\Name.php';

require_once 'Tool\Config.php';
require_once 'Tool\BirthdayData.php';
require_once 'Tool\Logger.php';

use Text\FontChecker;
use Text\TextSizeParser;
use Text\TextBox;
use Text\Name;

use Tool\BirthdayData;
use Tool\Logger;

class SendBirthdayEmails
{
    public function __construct() {
        // Set the timezone for date and email functions
        date_default_timezone_set('Asia/Shanghai');
    }

    public function generateImage($text, $dir, $withRef =false) {
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
        imagepng($image, "$dir/$text.png");
        imagedestroy($image);
    }

    public function getDataAndLog() {
        $data = BirthdayData::get(); $num = count($data);
        $day = date('m-d'); $time = date(DATE_RFC2822);

        $logger = new Logger;
        $logger->append("\n[$time]\t$day ($num)");
        foreach($data as $a) {
            $logger->append(implode(', ', $a));
        }
    }
}
