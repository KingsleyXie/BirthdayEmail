<?php
require_once 'SendBirthdayEmails.php';

use Tools\FontChecker;
use Tools\TextDataParser;

class Tests
{
    private $text_arr = [
        'abcde', '中文', '我能吞下玻璃而不伤身体',
        '中间·有间隔点', '堃', '隺', '000', '01中英文abc',
        'deep dark fantansy', 'deep♂dark♂fantansy',
        '假装繁体', 'ただいま', '輸入簡體字',
        '空 格', '在線', '坠后一个'
    ];

    private $name_arr = [
        '董华', '董建华', '董张宝华',
        '恶魔喵喵喵', '尼古拉斯·赵四',
        '拉斯特洛夫斯基·赵五六七八'
    ];

    private $alt_name_arr = [
        '董堃', '董建堃', '董张宝堃',
        '恶魔喵堃喵', '尼古拉斯·赵堃',
        '拉斯特洛堃斯基·赵五六七八'
    ];

    public function checkerTest() {
        $asset_path = getcwd() . '\Assets';
        $font_path = $asset_path . '\name.ttf';
        $font_path_alternate = $asset_path . '\alt.ttf';

        $fonts = [$font_path, $font_path_alternate];

        $checker = new FontChecker;
        foreach ($fonts as $font) {
            echo '<hr>' . $font . ':<br><br>';

            foreach ($this->text_arr as $str) {
                $ans = ($checker->isStringValid($str, $font) ? '√' : '×');
                echo "$str $ans<br>";
            }
        }
    }

    public function parserTest() {
        echo '<hr>Parser charLen() method:<br><br>';

        $parser = new TextDataParser;
        foreach ($this->text_arr as $str) {
            $len = $parser->charLen($str);
            echo "$str $len<br>";
        }

        echo '<hr>Parser parseIndex() method:<br><br>';;

        foreach (array_merge($this->name_arr, $this->alt_name_arr) as $name) {
            $ind = $parser->parseIndex($name);
            echo "$name $ind<br>";
        }
    }

    public function imageGenerateTest() {
        $dirs = [
            'TestGeneratedImages',
            'TestGeneratedImages/images',
            'TestGeneratedImages/alt-images'
        ];

        foreach ($dirs as $dir) {
            if (!is_dir($dir)) mkdir($dir);
        }

        $obj = new SendBirthdayEmails;
        foreach ($this->name_arr as $name) {
            $obj->generateImage($name, $dirs[1]);
        }

        foreach ($this->alt_name_arr as $name) {
            $obj->generateImage($name, $dirs[2]);
        }
    }
}

$test = new Tests;
$test->checkerTest();
$test->parserTest();
$test->imageGenerateTest();
