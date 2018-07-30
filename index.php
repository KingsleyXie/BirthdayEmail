<?php
require_once 'SendBirthdayEmails.php';

// Run every day on 12:00:
// 0 12 * * * cd path/to/directory && php index.php

$obj = new SendBirthdayEmails;
$obj->run();
