<?php
require_once 'FontLib\Autoloader.php';
require_once 'Tools\FontChecker.php';
require_once 'Tools\TextDataParser.php';

use Tools\FontChecker;
use Tools\TextDataParser;

class SendBirthdayEmails
{
    public function generateImage($name, $dir) {
        $image = imagecreatefrompng('Assets\card.png');
        $color = imagecolorallocate($image, 144, 139, 134);

        $asset_path = getcwd() . '\Assets';
        $font_path = $asset_path . '\name.ttf';
        $font_path_alternate = $asset_path . '\alt.ttf';

        $checker = new FontChecker;
        $parser = new TextDataParser;

        // Test Code
        // $checker->batchTest([$font_path, $font_path_alternate]);
        // $parser->test();

        $td = null;
        if ($checker->isStringValid($name, $font_path)) {
            // If current font supports the text
            // Calculate Text Related Data(Size & Position)
            $td = $parser->calc($name);
        } else {
            $font_path = $font_path_alternate;
            $td = $parser->calcAltFont($name);
        }

        imagettftext(
            $image,
            $td->size,
            0,
            $td->paddingLeft,
            $td->paddingTop,
            $color,
            $font_path,
            str_replace('·', "\n", $name)
        );

        imagepng($image, "$dir$name.png");
        imagedestroy($image);
    }
}

$name = '董华';
$obj = new SendBirthdayEmails;
$obj->generateImage($name, 'GeneratedImages/');
