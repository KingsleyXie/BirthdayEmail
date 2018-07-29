<?php
namespace Tool;

class Logger
{
    public static function append($type, $line) {
        $dir = 'logs/';
        if (!is_dir($dir)) mkdir($dir);

        $file = fopen("$dir$type.log", 'a+');
        fwrite($file, $line . "\n");
        fclose($file);
    }
}
