<#1>
<?php
require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/vendor/autoload.php';
xaliChecklist::updateDB();
xaliChecklistEntry::updateDB();
xaliSetting::updateDB();
xaliUserStatus::updateDB();
?>
<#2>
<?php
require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/vendor/autoload.php';
foreach (xaliChecklistEntry::where(array('status' => xaliChecklistEntry::STATUS_ABSENT_EXCUSED))->get() as $entry) {
	$entry->setStatus(xaliChecklistEntry::STATUS_ABSENT_UNEXCUSED);
	$entry->update();
}
?>
<#3>
<?php
require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/vendor/autoload.php';
xaliConfig::updateDB();
xaliAbsenceReason::updateDB();
xaliLastReminder::updateDB();
xaliAbsenceStatement::updateDB();
?>
<#4>
<?php
\srag\Plugins\AttendanceList\Notification\Notification\Notification::updateDB_();
\srag\Plugins\AttendanceList\Notification\Notification\Language\NotificationLanguage::updateDB_();

if (\srag\Notifications4Plugin\AttendanceList\Notification\Repository::getInstance(\srag\Plugins\AttendanceList\Notification\Notification\Notification::class, \srag\Plugins\AttendanceList\Notification\Notification\Language\NotificationLanguage::class)
		->migrateFromOldGlobalPlugin(\xaliChecklistEntry::NOTIFICATION_NAME) === null) {

	$notification = \srag\Notifications4Plugin\AttendanceList\Notification\Repository::getInstance(\srag\Plugins\AttendanceList\Notification\Notification\Notification::class, \srag\Plugins\AttendanceList\Notification\Notification\Language\NotificationLanguage::class)
		->factory()->newInstance();

	$notification->setName(\xaliChecklistEntry::NOTIFICATION_NAME);
	$notification->setTitle("Absence");
	$notification->setDescription("Mail which will be sent directly after a user has been defined as absent");
	$notification->setDefaultLanguage("en");

	$notification->setSubject("Absence", "en");
	$notification->setText("Hello {{user.getFirstname}} {{user.getLastname}},
	          
	    You were absent in one of your courses:
	         
	    {{absence}}
	          
	    Please click on the link and specify a reason for your absence.", "en");

	\srag\Notifications4Plugin\AttendanceList\Notification\Repository::getInstance(\srag\Plugins\AttendanceList\Notification\Notification\Notification::class, \srag\Plugins\AttendanceList\Notification\Notification\Language\NotificationLanguage::class)
		->storeInstance($notification);
}

if (\srag\Notifications4Plugin\AttendanceList\Notification\Repository::getInstance(\srag\Plugins\AttendanceList\Notification\Notification\Notification::class, \srag\Plugins\AttendanceList\Notification\Notification\Language\NotificationLanguage::class)
		->migrateFromOldGlobalPlugin(\xaliCron::NOTIFICATION_NAME) === null) {

	$notification = \srag\Notifications4Plugin\AttendanceList\Notification\Repository::getInstance(\srag\Plugins\AttendanceList\Notification\Notification\Notification::class, \srag\Plugins\AttendanceList\Notification\Notification\Language\NotificationLanguage::class)
		->factory()->newInstance();

	$notification->setName(\xaliCron::NOTIFICATION_NAME);
	$notification->setTitle("Absence Reminder");
	$notification->setDescription("Reminder email listing all open absence reasons");
	$notification->setDefaultLanguage("en");

	$notification->setSubject("Reminder: reasons for absence still open", "en");
	$notification->setText("Hello {{user.getFirstname}} {{user.getLastname}},
	          
	    You haven't yet specified the reason for your absence in the following courses:
	         
	    {{open_absences}}
	          
	    Please click on the link(s) and specify a reason for your absence.", "en");

	\srag\Notifications4Plugin\AttendanceList\Notification\Repository::getInstance(\srag\Plugins\AttendanceList\Notification\Notification\Notification::class, \srag\Plugins\AttendanceList\Notification\Notification\Language\NotificationLanguage::class)
		->storeInstance($notification);
}
?>
