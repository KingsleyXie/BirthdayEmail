<?php
require_once 'SendBirthdayEmails.php';

use Text\FontChecker;
use Text\TextSizeParser;

class CLITest
{
    private $text_arr = [
        'abcde', '中文', '我能吞下玻璃而不伤身体',
        '中间·有间隔点', '堃', '隺', '000', '01中英文abc',
        'deep dark fantansy', 'deep♂dark♂fantansy',
        '假装繁体', 'ただいま', '輸入簡體字',
        '空 格', '在線', '坠后一个'
    ];

    private $name_arr = [
        '张华', '章滑华', '章保滑华', '恶魔喵喵喵',
        '恶魔喵喵喵喵', '尼古拉斯·赵宝华'
    ];

    private $alt_name_arr = [
        '张堃', '章保堃', '章滑宝堃', '恶魔喵喵堃',
        '恶魔喵喵堃喵', '尼古拉斯·赵宝堃'
    ];

    public function checkerTest() {
        $asset_path = getcwd() . '/Assets';
        $font_path = $asset_path . '/name.ttf';
        $font_path_alternate = $asset_path . '/alt.ttf';

        $fonts = [$font_path, $font_path_alternate];

        $checker = new FontChecker;
        foreach ($fonts as $font) {
            echo "------\n$font:\n\n";

            foreach ($this->text_arr as $str) {
                $ans = ($checker->isStringValid($str, $font) ? '√' : '×');
                echo "$str $ans\n";
            }
        }
    }

    public function parserTest() {
        echo "------\nParser charLen() method:\n\n";

        $parser = new TextSizeParser;
        foreach ($this->text_arr as $str) {
            $len = $parser->charLen($str);
            echo "$str $len\n";
        }

        echo "------\nParser parseIndex() method:\n\n";;

        foreach (array_merge($this->name_arr, $this->alt_name_arr) as $name) {
            $ind = $parser->parseIndex($name);
            echo "$name $ind\n";
        }
    }

    public function imageGenerateTest() {
        // These are test directories
        $dirs = [
            'images/',
            'images/name/', 'images/alt-name/',
            'images/refed-name/', 'images/refed-alt-name/'
        ];

        foreach ($dirs as $dir) {
            if (!is_dir($dir)) mkdir($dir);
        }

        $obj = new SendBirthdayEmails;

        foreach ($this->name_arr as $name) {
            $obj->generateImage($name, $dirs[1] . $name . '.png');
        }
        foreach ($this->alt_name_arr as $name) {
            $obj->generateImage($name, $dirs[2] . $name . '.png');
        }

        foreach ($this->name_arr as $name) {
            $obj->generateImage($name, $dirs[3] . $name . '.png', true);
        }
        foreach ($this->alt_name_arr as $name) {
            $obj->generateImage($name, $dirs[4] . $name . '.png', true);
        }
    }
}

$test = new CLITest;
$test->checkerTest();
$test->parserTest();
$test->imageGenerateTest();
