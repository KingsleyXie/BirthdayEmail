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

// Check if current font supports the text
$checker = new FontChecker;
if (!$checker->isStringValid($text, $font_path)) $font_path = $font_path_alternate;

$parser = new TextDataParser;
$td = $parser->calc($text, true);

imagettftext($image, $td->size, 0, $td->paddingLeft, $td->paddingTop, $color, $font_path, $text);
imagepng($image); imagedestroy($image);

// Test Code
// $checker->batchTest([$font_path, $font_path_alternate]);
