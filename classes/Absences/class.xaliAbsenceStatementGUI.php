<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class xaliAbsenceStatementGUI
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xaliAbsenceStatementGUI extends xaliGUI {
	const CMD_UPDATE = 'update';
	const CMD_STANDARD = 'show';


	/**
	 *
	 */
	protected function show() {
		$absence = xaliAbsenceStatement::findOrGetInstance($_GET['entry_id']);
		$xaliAbsenceFormGUI = new xaliAbsenceStatementFormGUI($this, $absence);
		$this->tpl->setContent($xaliAbsenceFormGUI->getHTML());
	}


	/**
	 *
	 */
	protected function update() {
		$absence = xaliAbsenceStatement::findOrGetInstance($_GET['entry_id']);
		$xaliAbsenceFormGUI = new xaliAbsenceStatementFormGUI($this, $absence);
		$xaliAbsenceFormGUI->setValuesByPost();
		if ($xaliAbsenceFormGUI->saveForm()) {
			ilUtil::sendSuccess($this->pl->txt('msg_saved'), true);
			$this->ctrl->redirect($this, self::CMD_STANDARD);
		}
		$this->tpl->setContent($xaliAbsenceFormGUI->getHTML());
	}


	/**
	 *
	 */
	protected function cancel() {
		$this->ctrl->returnToParent($this);
	}
}