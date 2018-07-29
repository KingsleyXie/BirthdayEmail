<?php
namespace Tool;

class BirthdayData
{
    public static function get() {
        $conf = Config::$database;

        $con = new \mysqli(
            $conf['host'],
            $conf['username'], $conf['password'],
            $conf['database'], $conf['port']
        );
        $con->set_charset($conf['charset']);

        return $con->query($conf['select'])->fetch_all();
    }
}
