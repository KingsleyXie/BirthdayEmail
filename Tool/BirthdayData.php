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
        $query = $con->query($conf['select']);

        $arr = [];
        if ($query) {
            while ($row = $query->fetch_assoc())
                array_push($arr, $row);
        }
        return $arr;
    }
}
