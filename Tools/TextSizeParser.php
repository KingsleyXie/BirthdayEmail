<?php
namespace Tools;

class TextSizeParser {
    public function calc($text, $defaultFont) {
        $size = 80;
        $paddingLeft = 652;
        $paddingTop = 154;

        return [$size, $paddingLeft, $paddingTop];
    }
}
