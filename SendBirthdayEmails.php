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
    public function generateImage($name, $dir, $withRect =false) {
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
        $name = str_replace('·', "", $name);
        $box = new TextBox;
        $bounding = $box->getBounding($font_size, $font_path, $name);

        imagettftext(
            $image, $font_size, 0,
            $bounding['baseline_x'],
            $bounding['baseline_y'],
            $color, $font_path, $name
        );

        // Rectangles for debug
        if ($withRect) {
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
                $bounding['upper_left_x'],
                $bounding['upper_left_y'],
                $bounding['lower_right_x'],
                $bounding['lower_right_y'],
                $color
            );

            // Filename convertion to avoid png file name collisions
            $name = 'rect' . $name;
        }

        imagepng($image, "$dir/$name.png");
        imagedestroy($image);
    }
}
