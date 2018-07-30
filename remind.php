<?php
require_once 'SendReminds.php';

// Run every month on 10:00 1st:
// 0 10 1 * * cd path/to/directory && php remind.php

SendReminds::run();
