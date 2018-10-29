<?php
require_once 'SendBirthdayEmails.php';

// Run every day on 11:00:
// 0 11 * * * cd path/to/directory && php index.php

$obj = new SendBirthdayEmails;
$obj->run();
