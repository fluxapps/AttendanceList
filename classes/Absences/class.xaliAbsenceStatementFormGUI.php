<?php
class xaliAbsenceStatementFormGUI extends ilPropertyFormGUI {

	protected ilAttendanceListPlugin|ilPlugin $pl;
	protected ilLanguage $lng;
	protected ilCtrl $ctrl;
	protected \ILIAS\FileUpload\FileUpload $upload;
	protected ?xaliAbsenceStatement $absence_statement;


	/**
	 * xaliAbsenceStatementFormGUI constructor.
	 *
	 * @param                           $parent_gui
	 * @param xaliAbsenceStatement|NULL $xaliAbsenceStatement
	 */
	public function __construct($parent_gui, xaliAbsenceStatement $xaliAbsenceStatement = null) {
		global $DIC;
		$lng = $DIC['lng'];
		$ilCtrl = $DIC['ilCtrl'];
		$this->absence_statement = $xaliAbsenceStatement;
		$this->parent_gui = $parent_gui;
        /** @var $component_factory ilComponentFactory */
        $component_factory = $DIC['component.factory'];
        /** @var $plugin ilAttendanceListPlugin */
        $this->pl  = $component_factory->getPlugin(ilAttendanceListPlugin::PLUGIN_ID);
		$this->lng = $lng;
		$this->ctrl = $ilCtrl;
		$this->upload = $DIC->upload();

		parent::__construct();

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


	protected function initForm(): void
    {
		$input = new ilRadioGroupInputGUI($this->pl->txt('absence_reason'), 'reason_id');

		/** @var xaliAbsenceReason $reason */
		foreach (xaliAbsenceReason::get() as $reason) {
			$option = new ilRadioOption($reason->getTitle(), $reason->getId());
			if ($info = $reason->getInfo()) {
				$option->setInfo($info);
			}
			if ($reason->hasComment()) {
				$subinput = new ilTextAreaInputGUI($this->pl->txt('comment'), 'comment_' . $reason->getId());
				$subinput->setRequired($reason->getCommentReq());
				$option->addSubItem($subinput);
			}
			if ($reason->hasUpload()) {
				$subinput = new ilFileInputGUI($this->pl->txt('file_upload'), 'file_upload_' . $reason->getId());
				$subinput->setRequired($reason->getUploadReq()
					&& (!$this->absence_statement->getFileId() || ($this->absence_statement->getReasonId() != $reason->getId())));
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

	public function saveForm(): bool
    {
		if (!$this->checkInput()) {
			return false;
		}

		$reason_id = $this->getInput('reason_id');

		if ($this->absence_statement->getReasonId() != $reason_id) {
			if ($existing_file_id = $this->absence_statement->getFileId()) {
				$existing_file_obj = new ilObjFile($existing_file_id, false);
				$existing_file_obj->delete();
				$this->absence_statement->setFileId(null);
			}
			$this->absence_statement->setComment('');
		}

		$this->absence_statement->setReasonId($reason_id);

		/** @var xaliAbsenceReason $reason */
		$reason = xaliAbsenceReason::find($reason_id);
		if ($reason->hasComment()) {
			$comment = $this->getInput('comment_' . $reason_id);
			$this->absence_statement->setComment($comment);
		}

		if ($reason->hasUpload()) {
			$fileupload = $this->getInput('file_upload_' . $reason_id);
			if ($fileupload['size']) {
				$file_obj = new ilObjFile();
				$file_obj->setTitle($fileupload['name']);
				//$file_obj->setFileSize($fileupload['size']);
				//$file_obj->setFileName($fileupload['name']);
				$file_obj->setType('file');
				$file_obj->setMode("object");
				$file_obj->create();

				if (false === $this->upload->hasBeenProcessed()) {
					$this->upload->process();
				}


				$file_obj->getUploadFile($fileupload['tmp_name'], $fileupload["name"]);

				$this->absence_statement->setFileId($file_obj->getId());
			}
		}

		if (xaliAbsenceStatement::where(array('entry_id' => $this->absence_statement->getEntryId()))->hasSets()) {
			$this->absence_statement->update();
		} else {
			$this->absence_statement->create();
		}

		return true;
	}

	public function fillForm(): void
    {
        $filename = "";
		if ($file_id = $this->absence_statement->getFileId()) {
			$filename = ilObjFile::_lookupTitle($file_id);
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