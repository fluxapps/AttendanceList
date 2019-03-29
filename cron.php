<?php
chdir(substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], '/Customizing')));
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . './../../../UIComponent/UserInterfaceHook/Notifications4Plugins/vendor/autoload.php';
$cron = new xaliCron($_SERVER['argv']);
$cron->run();
$cron->logout();