<?php
require_once 'SendBirthdayEmails.php';

// Run every day on 08:00 AM:
// 0 8 * * * php /path/to/index.php

$obj = new SendBirthdayEmails;
$obj->run();
