<?php
require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';
require_once 'PHPMailer/Exception.php';

require_once 'Tool/Config.php';
require_once 'Tool/RemindData.php';
require_once 'Tool/Logger.php';
require_once 'Tool/BirthdayEmail.php';

use Tool\RemindData;
use Tool\Logger;
use Tool\BirthdayEmail;

class SendReminds
{
    public static function run() {
        // Set the timezone for date and email functions
        date_default_timezone_set('Asia/Shanghai');
        $month = date('m'); $stamp = date(DATE_RFC2822);
        $sep = str_repeat('-', 37);

        Logger::append('remind', "Month: $month\t[$stamp]\n");

        $remind = new RemindData;

        // Iterate over departments
        for ($dep = 1; $dep <= 10; $dep++) {
            $remind->setDepId($dep);

            $department = $remind->getDepartment($dep);
            Logger::append('remind', '【' . $department . '】');
            $subject = "【百步梯】 $department $month 月生日信息提醒";

            $ministers = $remind->getMinisters();
            if (count($ministers) > 0) {
                foreach ($ministers as $minister) {
                    $name = $minister['receiver']; $email = $minister['email'];
                    Logger::append('remind', "$name<$email>");
                }
            } else {
                Logger::append('remind', '（暂无）');
            }

            Logger::append('remind', $sep);
            $msg = '';

            $members = $remind->getMembers();
            if (count($members) > 0) {
                foreach ($members as $member) {
                    $line = $member['receiver'] . ($member['isDeleted'] ? '（已退部）：' : '：');
                    $line .= $member['birthdate'] . self::weekText($member['birthdate']);

                    Logger::append('remind', $line);
                    $msg .= "$line\n";
                }
            } else {
                $line = '（暂无）';
                Logger::append('remind', $line);
                $msg .= "$line\n";
            }

            Logger::append('remind', $sep);

            $names = $remind->getNames();
            $names == '' ? '（暂无）' : $names;
            Logger::append('remind', $names . "\n\n\n");
            $msg .= "\n\n$sep\n以下同学将会在生日当天收到生日贺卡邮件：\n\n$names\n";

            $birthdayEmail = new BirthdayEmail;
            try {
                $birthdayEmail->sendRemind($ministers, $subject, $msg);
            } catch (Exception $err) {
                $msg = "\n$stamp\n$err";
                Logger::append('remind-fail', $msg);
                $birthdayEmail->sendErrorMsg($msg);
            }
        }
    }

    public static function weekText($date) {
        $text = ['日', '一', '二', '三', '四', '五', '六'];
        $date = date('Y-') . str_replace('.', '-', $date);
        return ' 周' . $text[date('w', strtotime($date))];
    }
}
