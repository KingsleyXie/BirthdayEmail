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
    public function generateImage($name, $dir, $withRef =false) {
        $filename = str_replace('·', '', $name);

        $image = imagecreatefrompng('Assets/card.png');
        $color = imagecolorallocate($image, 144, 139, 134);

        $asset_path = getcwd() . '/Assets';
        $font_path = $asset_path . '/name.ttf';
        $font_path_alternate = $asset_path . '/alt.ttf';

        $checker = new FontChecker;
        $parser = new TextSizeParser;

        $font_size = 0;
        // Check string without the interval, so use $filename instead of $name
        if ($checker->isStringValid($filename, $font_path)) {
            $font_size = $parser->getSize($name);
        } else {
            // If current font does not support the name
            // Change it to another widely-supported font
            $font_path = $font_path_alternate;
            $font_size = $parser->getAltSize($name);
        }

        $name = str_replace('·', "\n", $name);
        $box = new TextBox;
        $bounding = $box->getBounding($font_size, $font_path, $name);

        imagettftext(
            $image, $font_size, 0,
            $bounding['baseline_x'],
            $bounding['baseline_y'],
            $color, $font_path, $name
        );

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
            imageline($image, 0, $bounding['upper_left_y'], 950, $bounding['upper_left_y'], $color);
            imageline($image, 0, $bounding['lower_right_y'], 950, $bounding['lower_right_y'], $color);

            // Filename convertion to avoid name collisions
            $filename = "Ref-$filename";
        }

        imagepng($image, "$dir/$filename.png");
        imagedestroy($image);
    }
}
