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
