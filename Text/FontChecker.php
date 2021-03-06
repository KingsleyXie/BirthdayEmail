<?php
/*
 * Idea And Implementations Are Mainly From StackOverflow And PHP Documentation:
 * https://stackoverflow.com/questions/22271634/how-do-i-deal-with-characters-unsupported-by-the-font-file-when-using-imagettfte#22335192
 * https://stackoverflow.com/questions/395832/how-to-get-code-point-number-for-a-given-character-in-a-utf-8-string
 * https://stackoverflow.com/questions/9438158/split-utf8-string-into-array-of-chars
 * http://php.net/manual/en/function.mb-split.php
 * http://php.net/manual/en/function.ord.php
 */

namespace Text;

use FontLib\Font;
use FontLib\TrueType\Collection;

class FontChecker
{
    // Note: This method does not support UTF-8 characters with four bytes
    private function ord_utf8($c) {
        $byte0 = ord(substr($c, 0));
        if ($byte0 < 0x80) return $byte0;

        $byte1 = ord(substr($c, 1));
        if ($byte0 < 0xE0) return (($byte0 & 0x1F) << 6) + ($byte1 & 0x3F);

        $byte2 = ord(substr($c, 2));
        return (($byte0 & 0x0F) << 12) + (($byte1 & 0x3F) << 6) + ($byte2 & 0x3F);
    }

    private function charInFont($char, $font) {
        $subtable = null;
        foreach($font->getData('cmap', 'subtables') as $_subtable) {
            if ($_subtable['platformID'] == 3
                && $_subtable['platformSpecificID'] == 1) {
                $subtable = $_subtable;
                break;
            }
        }

        $ord = $this->ord_utf8($char);
        if (isset($subtable['glyphIndexArray'][$ord])) return true;
        return false;
    }

    public function isStringValid($str, $font_path) {
        $font = Font::load($font_path);
        if ($font instanceof Collection) {
            $font = $font->getFont(0);
        }

        $chars = preg_split('//u', $str, null, PREG_SPLIT_NO_EMPTY);
        foreach ($chars as $char) {
            if (!$this->charInFont($char, $font)) return false;
        }
        return true;
    }
}
