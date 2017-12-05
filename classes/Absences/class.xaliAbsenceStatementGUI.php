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
	const CMD_DOWNLOAD_FILE = 'downloadFile';


	/**
	 *
	 */
	protected function show() {
		$absence = xaliAbsenceStatement::findOrGetInstance($_GET['entry_id']);
		$xaliAbsenceFormGUI = new xaliAbsenceStatementFormGUI($this, $absence);
		$xaliAbsenceFormGUI->fillForm();
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
			$this->cancel();
		}
		$this->tpl->setContent($xaliAbsenceFormGUI->getHTML());
	}

	protected function downloadFile() {
		$file_id = $_GET['file_id'];
		$fileObj = new ilObjFile($file_id, false);
		$fileObj->sendFile();
		exit;
	}

	/**
	 *
	 */
	protected function cancel() {
		if ($back_cmd = $_GET['back_cmd']) {
			$this->ctrl->setParameterByClass(xaliOverviewGUI::class, 'entry_id', $_GET['entry_id']);
			$this->ctrl->redirectByClass(xaliOverviewGUI::class, $back_cmd);
		}
		$this->ctrl->returnToParent($this);
	}
}