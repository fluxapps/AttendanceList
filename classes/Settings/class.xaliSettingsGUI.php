<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/classes/class.xaliGUI.php';
require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/classes/Checklist/class.xaliChecklist.php';
require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/classes/UserStatus/class.xaliUserStatus.php';
require_once 'class.xaliSettingsFormGUI.php';
/**
 * Class xaliSettingsGUI
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xaliSettingsGUI extends xaliGUI {

	const CMD_STANDARD = 'showContent';
	const CMD_SAVE = 'save';

	public function showContent() {
		$xaliSettingsFormGUI = new xaliSettingsFormGUI($this, $this->parent_gui->object);
		$this->tpl->setContent($xaliSettingsFormGUI->getHTML());
	}

	public function save() {
		$xaliSettingsFormGUI = new xaliSettingsFormGUI($this, $this->parent_gui->object);
		$xaliSettingsFormGUI->setValuesByPost();
		if ($xaliSettingsFormGUI->saveSettings()) {

			// update LP
			xaliUserStatus::updateUserStatuses($this->parent_gui->obj_id);

			ilUtil::sendSuccess($this->lng->txt('saved_successfully'), true);
			$this->ctrl->redirect($this, self::CMD_STANDARD);
			return;
		}
		$this->tpl->setContent($xaliSettingsFormGUI->getHTML());
	}
}