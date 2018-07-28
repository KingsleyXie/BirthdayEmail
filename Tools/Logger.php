<?php
namespace Tools;

require_once '../Config.php';

class Logger
{
    public function log($line, $success =true) {
        $log = \Config::$log;
        $dir = $log['dir'];
        if (!is_dir($dir)) mkdir($dir);

        if ($success) $file = fopen($dir . $log['succ'], 'a+');
        else $file = fopen($dir . $log['fail'], 'a+');

        fwrite($file, $line . "\n");
        fclose($file);
    }
}
