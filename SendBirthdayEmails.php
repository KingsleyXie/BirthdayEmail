<?php
require_once 'FontLib\Autoloader.php';
require_once 'Tools\FontChecker.php';
require_once 'Tools\TextBox.php';
require_once 'Tools\TextDataParser.php';

use Tools\FontChecker;
use Tools\TextBox;
use Tools\TextDataParser;

class SendBirthdayEmails
{
    public function generateImage($name, $dir) {
        $image = imagecreatefrompng('Assets/card.png');
        $color = imagecolorallocate($image, 144, 139, 134);

        $asset_path = getcwd() . '/Assets';
        $font_path = $asset_path . '/name.ttf';
        $font_path_alternate = $asset_path . '/alt.ttf';

        $checker = new FontChecker;
        $parser = new TextDataParser;

        $td = null;
        if ($checker->isStringValid($name, $font_path)) {
            // If current font supports the text
            // Calculate Text Related Data(Size & Position)
            $td = $parser->calc($name);
        } else {
            $font_path = $font_path_alternate;
            $td = $parser->calcAltFont($name);
        }

        // imagettftext(
        //     $image,
        //     $td->size,
        //     0,
        //     $td->paddingLeft,
        //     $td->paddingTop,
        //     $color,
        //     $font_path,
        //     str_replace('·', "\n", $name)
        // );
        imagerectangle(
            $image,
            TextBox::$RECT['left'],
            TextBox::$RECT['top'],
            TextBox::$RECT['right'],
            TextBox::$RECT['bottom'],
            $color
        );

        imagepng($image, "$dir/$name.png");
        imagedestroy($image);
    }
}
$obj = new SendBirthdayEmails;
$obj->generateImage('董建堃', './');
