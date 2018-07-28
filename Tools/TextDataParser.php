<?php
namespace Tools;

class TextData
{
    public $text, $size, $paddingLeft, $paddingTop;
}

class TextDataParser
{
    private function charLen($text) {
        $text = str_replace('·', '', $text);
        return count(preg_split('//u', $text, null, PREG_SPLIT_NO_EMPTY));
    }

    public function calc($text, $defaultFont) {
        $td = new TextData;

        $text = str_replace('·', "\n", $text);
        $td->size = 80;
        $td->paddingLeft = 652;
        $td->paddingTop = 154;

        return $td;
    }

    public function test() {
        $arr = [
            'abcde', '中文', '我能吞下玻璃而不伤身体',
            '中间·有间隔点', '堃', '隺', '000', '01中英文abc',
            'deep dark fantansy', 'deep♂dark♂fantansy',
            '假装繁体', 'ただいま', '輸入簡體字',
            '空 格', '在線', '坠后一个'
        ];

        foreach ($arr as $str) {
            $len = $this->charLen($str);
            echo "$str $len<br>";
        }
    }
}
