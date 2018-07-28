<?php
namespace Tools;

class TextSizeParser
{
    public function charLen($text) {
        return count(preg_split('//u', $text, null, PREG_SPLIT_NO_EMPTY));
    }

    public function parseIndex($text) {
        if (strpos($text, '·') !== false) return 5;    // characters with interval

        $len = $this->charLen($text);
        if ($len < 6) return $len - 2;                 // [2, 5] characters
        return 4;                                      // > 5 characters
    }

    public function getSize($text) {
        $fontSize = [
            87,   // == 2 characters                   => '张华'
            70,   // == 3 characters                   => '章滑华'
            63,   // == 4 characters                   => '章保滑华'
            52,   // == 5 characters                   => '恶魔喵喵喵'
            35,   // > 5 characters                    => '恶魔喵喵喵喵'
            43    // characters with interval          => '尼古拉斯·赵宝华'
        ];

        return $fontSize[$this->parseIndex($text)];
    }

    public function getAltSize($text) {
        $fontSize = [
            63,   // == 2 characters                   => '张堃'
            57,   // == 3 characters                   => '章保堃'
            51,   // == 4 characters                   => '章滑宝堃'
            40,   // == 5 characters                   => '恶魔喵喵堃'
            29,   // > 5 characters                    => '恶魔喵喵堃喵'
            33    // characters with interval          => '尼古拉斯·赵宝堃'
        ];

        return $fontSize[$this->parseIndex($text)];
    }
}
