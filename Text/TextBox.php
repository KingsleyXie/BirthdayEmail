<?php
// Solution for a font baseline drawing related bug comes from PHP.net:
// http://php.net/manual/en/function.imagettfbbox.php#75407

namespace Text;

class TextBox
{
    public static $RECT = [
        'left' => 630, 'top' => 80,
        'right' => 860, 'bottom' => 200
    ];

    public static function getBounding($size, $font, $name, $center =true) {
        $rect = self::$RECT;
        $innerBox = imagettfbbox($size, 0, $font, $name);

        $outterHeight = $rect['bottom'] - $rect['top'];
        $outterWidth = $rect['right'] - $rect['left'];

        // (This method is too naive)
        // $innerHeight = abs($innerBox[5] - $innerBox[1]);
        // $innerWidth = abs($innerBox[4] - $innerBox[0]);

        $innerWidth = abs($innerBox[4] - $innerBox[0]);
        if($innerBox[0] < -1) {
            $innerWidth = abs($innerBox[4]) + abs($innerBox[0]) - 1;
        }

        $innerHeight = abs($innerBox[5]) - abs($innerBox[1]);
        if($innerBox[1] > 0) {
            $innerHeight = abs($innerBox[5] - $innerBox[1]) - 1;
        }

        if($innerBox[0] >= -1) {
            $baseX = abs($innerBox[0] + 1) * -1;
        } else {
            $baseX = abs($innerBox[0] + 2);
        }
        $baseY = abs($innerBox[5] + 1);

        $bounding = [
            'upper_left_x' => $rect['left'],
            'upper_left_y' => $rect['top'],
            'baseline_x' => $rect['left'] + $baseX,
            'baseline_y' => $rect['top'] + $baseY
        ];

        $bounding['lower_right_x'] = $bounding['upper_left_x'] + $innerWidth;
        $bounding['lower_right_y'] = $bounding['upper_left_y'] + $innerHeight;

        // Vertical Center Alignments
        $offsetY = ($outterHeight - $innerHeight) / 2;
        $bounding['baseline_y'] += $offsetY;
        $bounding['upper_left_y'] += $offsetY;
        $bounding['lower_right_y'] += $offsetY;

        // Horizontal Center Alignment
        $offsetX = ($outterWidth - $innerWidth) / 2;
        if ($center && $offsetX > 0) {
            $bounding['baseline_x'] += $offsetX;
            $bounding['upper_left_x'] += $offsetX;
            $bounding['lower_right_x'] += $offsetX;
        }

        return $bounding;
    }
}
