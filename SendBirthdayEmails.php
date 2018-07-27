<?php
header('Content-type: image/png');

$image = imagecreatefrompng('Assets\card.png');
$color = imagecolorallocate($image, 144, 139, 134);
$font = getcwd() . '\Assets\name.ttf';

$text = "董建华";

imagettftext($image, 80, 0, 652, 154, $color, $font, $text);

imagepng($image);

imagedestroy($image);
