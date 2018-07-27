<?php
/*
 * Idea And `ord_utf8()` Function Are From StackOverflow:
 * https://stackoverflow.com/questions/22271634/how-do-i-deal-with-characters-unsupported-by-the-font-file-when-using-imagettfte#22335192
 * https://stackoverflow.com/questions/395832/how-to-get-code-point-number-for-a-given-character-in-a-utf-8-string#11708867
 */

namespace Tools;

require_once 'FontLib/Autoloader.php';

use FontLib\Font;
use FontLib\TrueType\Collection;

class FontFallback {
    var $font;

    private function ord_utf8($c) {
        $b0 = ord($c[0]);
        if ($b0 < 0x80) return $b0;

        $b1 = ord($c[1]);
        if ($b0 < 0xE0) return (($b0 & 0x1F) << 6) + ($b1 & 0x3F);

        return (($b0 & 0x0F) << 12) + (($b1 & 0x3F) << 6) + (ord($c[2]) & 0x3F);
    }

    public function charInFont($c) {
        $subtable = null;
        foreach($font->getData("cmap", "subtables") as $_subtable) {
            if ($_subtable["platformID"] == 3
                && $_subtable["platformSpecificID"] == 1) {
                $subtable = $_subtable;
                break;
            }
        }

        if (isset($subtable["glyphIndexArray"][ord_utf8($c)])) return true;
        return false;
    }

    public function isStringValid($str, $font) {
        $font = Font::load($font);
        if ($font instanceof Collection) {
            $font = $font->getFont(0);
        }

        foreach ($str_split($str) as $char) {
            if (!charInFont($char)) return false;
        }
        return true;
    }
}
