<?php
chdir(substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], '/Customizing')));
require_once __DIR__ . '/vendor/autoload.php';
$cron = new xaliCron($_SERVER['argv']);
$cron->run();
$cron->logout();