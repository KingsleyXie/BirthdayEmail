<?php
namespace Tools;

class TextSizeParser
{
    public function charLen($text) {
        return count(preg_split('//u', str_replace('·', '', $text), null, PREG_SPLIT_NO_EMPTY));
    }

    public function parseIndex($text) {
        $len = $this->charLen($text);

        if ($len < 5) return $len - 2;
        if (strpos($text, '·') !== false) {
            if ($len <= 10) return 4;
            return 5;
        }
        return 3;
    }

    public function getSize($text) {
        $fontSize = [
            12,   // == 2 characters                   => '董华'
            65,   // == 3 characters                   => '董建华'
            12,   // == 4 characters                   => '董张宝华'
            12,   // > 4 characters                    => '恶魔喵喵喵'
            12,   // <= 10 characters with interval    => '尼古拉斯·赵四'
            32   // < 10 characters with interval     => '拉斯特洛夫斯基·赵五六七八'
        ];
        
        return $fontSize[$this->parseIndex($text)];
    }

    public function getAltSize($text) {
        $fontSize = [
            17,   // == 2 characters                   => '董堃'
            17,   // == 3 characters                   => '董建堃'
            17,   // == 4 characters                   => '董张宝堃'
            17,   // > 4 characters                    => '恶魔喵堃喵'
            17,   // <= 10 characters with interval    => '尼古拉斯·赵堃'
            17   // < 10 characters with interval     => '拉斯特洛堃斯基·赵五六七八'
        ];

        return $fontSize[$this->parseIndex($text)];
    }
}
