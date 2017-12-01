<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
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