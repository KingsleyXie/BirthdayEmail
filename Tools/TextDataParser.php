<?php
namespace Tools;

class TextData
{
    public $size, $paddingLeft, $paddingTop;
}

class TextDataParser
{
    public function calc($text, $defaultFont) {
        $td = new TextData;

        $td->size = 80;
        $td->paddingLeft = 652;
        $td->paddingTop = 154;

        return $td;
    }
}
