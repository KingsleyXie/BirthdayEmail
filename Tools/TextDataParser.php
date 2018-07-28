<?php
namespace Tools;

class TextData
{
    public $size, $paddingLeft, $paddingTop;

    public function setData($arr) {
        $this->size = $arr[0];
        $this->paddingLeft = $arr[1];
        $this->paddingTop = $arr[2];
    }
}

// Hope one day I can find some algorithm or anything else
// that is elegant to calculate out these text data
// instead of just guess and try and record them = =
class TextDataParser
{
    private function charLen($text) {
        return count(preg_split('//u', str_replace('·', '', $text), null, PREG_SPLIT_NO_EMPTY));
    }

    private function parseIndex($text) {
        $len = $this->charLen($text);

        if ($len < 5) return $len - 2;
        if (strpos($text, '·') !== false) {
            if ($len <= 10) return 4;
            return 5;
        }
        return 3;
    }

    public function calc($text) {
        $data_lib = [
            [80, 670, 154],   // == 2 characters                   => '董华'
            [80, 652, 154],   // == 3 characters                   => '董建华'
            [61, 647, 147],   // == 4 characters                   => '董张宝华'
            [42, 652, 143],   // > 4 characters                    => '恶魔喵喵喵'
            [45, 666, 116],   // <= 10 characters with interval    => '尼古拉斯·赵四'
            [35, 662, 116],   // < 10 characters with interval     => '拉斯特洛夫斯基·赵五六七八'
        ];

        $td = new TextData;
        $td->setData($data_lib[$this->parseIndex($text)]);

        return $td;
    }

    public function calcAltFont($text) {
        $data_lib = [
            [53, 675, 146],   // == 2 characters                   => '董堃'
            [52, 663, 144],   // == 3 characters                   => '董建堃'
            [45, 657, 143],   // == 4 characters                   => '董张宝堃'
            [32, 660, 139],   // > 4 characters                    => '恶魔喵堃喵'
            [33, 666, 111],   // <= 10 characters with interval    => '尼古拉斯·赵堃'
            [27, 662, 109],   // < 10 characters with interval     => '拉斯特洛堃斯基·赵五六七八'
        ];

        $td = new TextData;
        $td->setData($data_lib[$this->parseIndex($text)]);

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
