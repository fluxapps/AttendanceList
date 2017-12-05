<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
/**
 * Class xaliAbsenceStatementFormGUI
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xaliAbsenceStatementFormGUI extends ilPropertyFormGUI {

	/**
	 * @var ilAttendanceListPlugin
	 */
	protected $pl;
	/**
	 * @var ilLanguage
	 */
	protected $lng;
	/**
	 * @var ilCtrl
	 */
	protected $ctrl;
	/**
	 * @var xaliAbsenceStatement
	 */
	protected $absence_statement;


	/**
	 * xaliAbsenceStatementFormGUI constructor.
	 *
	 * @param                           $parent_gui
	 * @param xaliAbsenceStatement|NULL $xaliAbsenceStatement
	 */
	public function __construct($parent_gui, xaliAbsenceStatement $xaliAbsenceStatement = null) {
		global $lng, $ilCtrl;
		$this->absence_statement = $xaliAbsenceStatement;
		$this->parent_gui = $parent_gui;
		$this->pl = ilAttendanceListPlugin::getInstance();
		$this->lng = $lng;
		$this->ctrl = $ilCtrl;

		if ($file_id = $this->absence_statement->getFileId()) {
			$this->ctrl->setParameter($this->parent_gui, 'file_id', $file_id);
		}
		$this->ctrl->setParameter($this->parent_gui, 'back_cmd', $_GET['back_cmd']);
		$this->ctrl->setParameter($this->parent_gui, 'entry_id', $xaliAbsenceStatement->getEntryId());
		$this->setFormAction($this->ctrl->getFormAction($this->parent_gui));
		$this->initForm();
		$date = xaliChecklist::find(xaliChecklistEntry::find($xaliAbsenceStatement->getEntryId())->getChecklistId())->getChecklistDate();
		$this->setTitle(sprintf($this->pl->txt('form_title_absence'), $date));
	}


	/**
	 *
	 */
	protected function initForm() {
		$input = new ilRadioGroupInputGUI($this->pl->txt('absence_reason'), 'reason_id');

		/** @var xaliAbsenceReason $reason */
		foreach (xaliAbsenceReason::get() as $reason) {
			$option = new ilRadioOption($reason->getTitle(), $reason->getId());
			if ($info = $reason->getInfo()) {
				$option->setInfo($info);
			}
			if ($reason->getCommentReq()) {
				$subinput = new ilTextAreaInputGUI($this->pl->txt('comment'), 'comment_' . $reason->getId());
				$subinput->setRequired(true);
				$option->addSubItem($subinput);
			}
			if ($reason->getUploadReq()) {
				$subinput = new ilFileInputGUI($this->pl->txt('file_upload'), 'file_upload_' . $reason->getId());
				$subinput->setRequired(true);
				$option->addSubItem($subinput);
			}
			$input->addOption($option);
		}

		$this->addItem($input);

		// Buttons
		if ($file_id = $this->absence_statement->getFileId()) {
			$this->addCommandButton(xaliAbsenceStatementGUI::CMD_DOWNLOAD_FILE, $this->pl->txt('download_file'));
		}
		$this->addCommandButton(xaliAbsenceStatementGUI::CMD_UPDATE,$this->lng->txt('save'));
		$this->addCommandButton(xaliAbsenceStatementGUI::CMD_CANCEL,$this->lng->txt('cancel'));
	}


	/**
	 * @return bool
	 */
	public function saveForm() {
		if (!$this->checkInput()) {
			return false;
		}

		$reason_id = $this->getInput('reason_id');
		$this->absence_statement->setReasonId($reason_id);

		/** @var xaliAbsenceReason $reason */
		$reason = xaliAbsenceReason::find($reason_id);
		if ($reason->getCommentReq()) {
			$comment = $this->getInput('comment_' . $reason_id);
			$this->absence_statement->setComment($comment);
		}

		if ($reason->getUploadReq()) {
			$fileupload = $this->getInput('file_upload_' . $reason_id);
			$file_obj = new ilObjFile();
			$file_obj->setTitle($fileupload['name']);
			$file_obj->setFileSize($fileupload['size']);
			$file_obj->setFileName($fileupload['name']);
			$file_obj->setType('file');
			$file_obj->setMode("object");
			$file_obj->create();

			$file_obj->getUploadFile($fileupload['tmp_name'], $fileupload["name"]);

			$this->absence_statement->setFileId($file_obj->getId());
		}

		if (xaliAbsenceStatement::where(array('entry_id' => $this->absence_statement->getEntryId()))->hasSets()) {
			$this->absence_statement->update();
		} else {
			$this->absence_statement->create();
		}

		return true;
	}

	public function fillForm() {
		if ($file_id = $this->absence_statement->getFileId()) {
			$filename = ilObjFile::_lookupFileName($file_id);
		}
		$reason_id = $this->absence_statement->getReasonId();
		$values = array(
			'reason_id' => $reason_id,
			'comment_' . $reason_id => $this->absence_statement->getComment(),
			'file_upload_' . $reason_id => $filename,
		);
		$this->setValuesByArray($values);
	}
}