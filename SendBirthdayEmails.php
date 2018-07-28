<?php
require_once 'FontLib\Autoloader.php';
require_once 'Tools\FontChecker.php';
require_once 'Tools\TextDataParser.php';

use Tools\FontChecker;
use Tools\TextDataParser;

header('Content-type: image/png');

$image = imagecreatefrompng('Assets\card.png');
$color = imagecolorallocate($image, 144, 139, 134);

$asset_path = getcwd() . '\Assets';
$font_path = $asset_path . '\name.ttf';
$font_path_alternate = $asset_path . '\alt.ttf';

$text = "董建华";

$checker = new FontChecker;
$parser = new TextDataParser;

// Test Code
// $checker->batchTest([$font_path, $font_path_alternate]);
// $parser->test();

$td = null;
if ($checker->isStringValid($text, $font_path)) {
    // If current font supports the text
    // Calculate Text Related Data(Size & Position)
    $td = $parser->calc($text);
} else {
    $font_path = $font_path_alternate;
    $td = $parser->calcAltFont($text);
}

imagettftext($image, $td->size, 0, $td->paddingLeft, $td->paddingTop, $color, $font_path, $td->text);
imagepng($image); imagedestroy($image);
