<?php
require_once 'FontLib\Autoloader.php';
require_once 'Tools\FontChecker.php';
require_once 'Tools\TextSizeParser.php';

use Tools\FontChecker;
use Tools\TextSizeParser;

header('Content-type: image/png');

$image = imagecreatefrompng('Assets\card.png');
$color = imagecolorallocate($image, 144, 139, 134);

$font_path = getcwd() . '\Assets\name.ttf';
$font_path_alternate = getcwd() . '\Assets\alt.ttf';

$text = "董建华";

// Check if current font supports the text
$checker = new FontChecker;
if (!$checker->isStringValid($text, $font_path)) $font_path = $font_path_alternate;

$textSize = new TextSizeParser;
$ts = $textSize->calc($text, true);

imagettftext($image, $ts[0], 0, $ts[1], $ts[2], $color, $font_path, $text);
imagepng($image); imagedestroy($image);

// Test Code
// $checker->batchTest([$font_path, $font_path_alternate]);
