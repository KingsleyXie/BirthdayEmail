<?php
require_once 'FontLib\Autoloader.php';
require_once 'Tools\FontChecker.php';

use Tools\FontChecker;

// header('Content-type: image/png');

$image = imagecreatefrompng('Assets\card.png');
$color = imagecolorallocate($image, 144, 139, 134);

$font_path = getcwd() . '\Assets\name.ttf';
$font_path_alternate = getcwd() . '\Assets\alt.ttf';

$text = "董建华";

// Check if current font supports the text
$checker = new FontChecker;
$checker->batchTest([$font_path, $font_path_alternate]);

// if (!$checker->test($text, $font_path)) $font_path = $font_path_alternate;
/*
imagettftext($image, 80, 0, 652, 154, $color, $font_path, $text);
imagepng($image);
imagedestroy($image);
*/