<?php
class Config
{
    public static $database = [
            'host' => '127.0.0.1',
            'port' => '3306',
            'charset' => 'utf8',
            'database' => 'DATABASE_NAME',
            'username' => 'DATABASE_USERNAME',
            'password' => 'DATABSE_PASSWORD',
            'select' => 'SELECT * FROM table'
    ];

    public static $mail = [
            'timezone' => 'Asia/Shanghai',
            'host' => 'MAIL_SMTP_HOST',
            'port' => 465,
            'secure' => 'ssl',
            'username' => 'MAIL_USERNAME',
            'password' => 'MAIL_PASSWORD'
    ];

    public static $log = [
            'dir' => '/var/log/BirthdayEmail/',
            'succ' => 'succ.log',
            'fail' => 'fail.log'
    ];
}
