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

    /**
     * @throws ilCtrlException
     */
    public function showContent(): void
    {
		$xaliSettingsFormGUI = new xaliSettingsFormGUI($this, $this->parent_gui->getObject());
		$this->tpl->setContent($xaliSettingsFormGUI->getHTML());
	}

	public function save(): void
    {
		$xaliSettingsFormGUI = new xaliSettingsFormGUI($this, $this->parent_gui->getObject());
		$xaliSettingsFormGUI->setValuesByPost();
		if ($xaliSettingsFormGUI->saveSettings()) {

			// update LP
			xaliUserStatus::updateUserStatuses($this->parent_gui->getObject()->getId());

            $this->tpl->setOnScreenMessage('success',  self::dic()->language()->txt("saved_successfully"), true);
			$this->ctrl->redirect($this, self::CMD_STANDARD);
			return;
		}
		$this->tpl->setContent($xaliSettingsFormGUI->getHTML());
	}
}