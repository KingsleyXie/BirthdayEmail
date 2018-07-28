<?php
require_once 'FontLib\Autoloader.php';
require_once 'Tools\FontChecker.php';
require_once 'Tools\TextSizeParser.php';
require_once 'Tools\TextBox.php';

use Tools\FontChecker;
use Tools\TextSizeParser;
use Tools\TextBox;

class SendBirthdayEmails
{
    public function generateImage($name, $dir) {
        $image = imagecreatefrompng('Assets/card.png');
        $color = imagecolorallocate($image, 144, 139, 134);

        $asset_path = getcwd() . '/Assets';
        $font_path = $asset_path . '/name.ttf';
        $font_path_alternate = $asset_path . '/alt.ttf';

        $checker = new FontChecker;
        $parser = new TextSizeParser;

        $font_size = 0;
        if ($checker->isStringValid($name, $font_path)) {
            $font_size = $parser->getSize($name);
        } else {
            // If current font does not support the name
            // Change it to another wide-range font
            $font_path = $font_path_alternate;
            $font_size = $parser->getAltSize($name);
        }

        // TODO: Process multi-line situation
        $name = str_replace('·', "\n", $name);
        $box = new TextBox;
        $padding = $box->getPoints($font_size, $font_path, $name);

        // Rectangles for debug
        if (true) {
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
            $box = imagettfbbox($font_size, 0, $font_path, $name);
            imagerectangle(
                $image,
                $padding['lower_left_x'],
                $padding['lower_left_y'],
                $padding['upper_right_x'],
                $padding['upper_right_y'],
                $color
            );
        }

        imagettftext(
            $image, $font_size, 0,
            $padding['lower_left_x'],
            $padding['lower_left_y'],
            $color, $font_path, $name
        );

        imagepng($image, "$dir/$name.png");
        imagedestroy($image);
    }
}

$obj = new SendBirthdayEmails;
// $obj->generateImage('董建堃', './');
$obj->generateImage('赵四', './');
