<?php
chdir(substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], '/Customizing')));
require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/classes/class.xaliCron.php';
$cron = new xaliCron($_SERVER['argv']);
$cron->run();
$cron->logout();