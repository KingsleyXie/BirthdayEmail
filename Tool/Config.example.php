<?php
namespace Tool;

class Config
{
    public static $database = [
        'host' => '127.0.0.1',
        'port' => '3306',
        'charset' => 'utf8',

        'database' => 'DATABASE_NAME',
        'username' => 'DATABASE_USERNAME',
        'password' => 'DATABSE_PASSWORD',

        'select' => 'SELECT * FROM table',
        'test' =>
            'SELECT "董建华" AS receiver, "name@site.com" AS email
            UNION SELECT "章保滑", "name@site.com"
            UNION SELECT "某某某", "wrong_addr"'
    ];

    public static $mail = [
        'host' => 'MAIL_SMTP_HOST',
        'port' => 465,
        'secure' => 'ssl',

        'username' => 'MAIL_USERNAME',
        'password' => 'MAIL_PASSWORD',
        'address' => 'MAIL_ADDRESS',
        'name' => 'SENDER_NAME',

        'card' => [
            'subject' => '你们给我搞的这个 Email 啊，Excited！',
            'body' => 'Something like this: <img src="cid:card">xxxxxx',
            'altbody' => 'Fot Those Does Not Support HTML',
        ],

        'error' => [
            'subject' => 'An Error Occured = =',
            'admin' => [
                'admin1@site.com',
                'admin2@site.com',
                'admin3@site.com'
            ]
        ]
    ];
}
