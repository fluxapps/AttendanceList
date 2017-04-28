<#1>
<?php
require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/classes/Checklist/class.xaliChecklist.php';
require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/classes/Checklist/class.xaliChecklistEntry.php';
require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/classes/Settings/class.xaliSetting.php';
require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/classes/UserStatus/class.xaliUserStatus.php';
xaliChecklist::updateDB();
xaliChecklistEntry::updateDB();
xaliSetting::updateDB();
xaliUserStatus::updateDB();
?>