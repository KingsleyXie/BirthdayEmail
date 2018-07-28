<?php
namespace Tools;

class TextPaddingParser
{
    public static $RECT = [
        'left' => 630,
        'top' => 80,
        'right' => 860,
        'bottom' => 200
    ];

    public function getPadding($size, $font, $name, $center =true) {
        $innerBox = imagettfbbox($size, 0, $font, $name);
        $rect = TextPaddingParser::$RECT;

        $outterHeight = $rect['bottom'] - $rect['top'];
        $innerHeight = abs($innerBox[5] - $innerBox[1]);

        $outterWidth = $rect['right'] - $rect['left'];
        $innerWidth = abs($innerBox[4] - $innerBox[0]);

        $ans = [
            'lower_left_x' => $rect['left'],
            'lower_left_y' => ($rect['bottom'] - ($outterHeight - $innerHeight) / 2)
        ];

        $ans['upper_right_x'] = $ans['lower_left_x'] + $innerWidth;
        $ans['upper_right_y'] = $ans['lower_left_y'] - $innerHeight;

        if ($center) {
            $offset = ($outterWidth - $innerWidth) / 2;
            $ans['lower_left_x'] += $offset;
            $ans['upper_right_x'] += $offset;
        }

        return $ans;
    }
}
