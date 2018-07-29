<?php
namespace Tool;

class Logger
{
    public static function append($type, $line) {
        $log = Config::$log;
        $dir = $log['dir'];
        if (!is_dir($dir)) mkdir($dir);

        $file = fopen($dir . $log[$type], 'a+');
        fwrite($file, $line . "\n");
        fclose($file);
    }
}
