<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
/**
 * Class xaliConfigFormGUI
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xaliConfigAbsenceFormGUI extends ilPropertyFormGUI {

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
	 * @var xaliAbsenceReason
	 */
	protected $absence_reason;

	/**
	 * xaliConfigFormGUI constructor.
	 */
	public function __construct($parent_gui, xaliAbsenceReason $absence_reason) {
		global $lng, $ilCtrl;
		$this->parent_gui = $parent_gui;
		$this->pl = ilAttendanceListPlugin::getInstance();
		$this->lng = $lng;
		$this->ctrl = $ilCtrl;
		$this->absence_reason = $absence_reason;

		if ($ar_id = $this->absence_reason->getId()) {
			$this->ctrl->setParameter($this->parent_gui, 'ar_id', $ar_id);
		}
		$this->setFormAction($this->ctrl->getFormAction($this->parent_gui));
		$this->initForm();
	}


	/**
	 *
	 */
	protected function initForm() {
		$subinput = new ilTextInputGUI($this->pl->txt('table_column_' . xaliAbsenceReason::F_ABSENCE_REASONS_TITLE), xaliAbsenceReason::F_ABSENCE_REASONS_TITLE);
		$subinput->setRequired(true);
		$this->addItem($subinput);

		$subinput = new ilTextInputGUI($this->pl->txt('table_column_' . xaliAbsenceReason::F_ABSENCE_REASONS_INFO), xaliAbsenceReason::F_ABSENCE_REASONS_INFO);
		$this->addItem($subinput);

		$subinput = new ilCheckboxInputGUI($this->pl->txt('table_column_' . xaliAbsenceReason::F_ABSENCE_REASONS_COMMENT), xaliAbsenceReason::F_ABSENCE_REASONS_COMMENT);
		$this->addItem($subinput);

		$subinput = new ilCheckboxInputGUI($this->pl->txt('table_column_' . xaliAbsenceReason::F_ABSENCE_REASONS_UPLOAD), xaliAbsenceReason::F_ABSENCE_REASONS_UPLOAD);
		$this->addItem($subinput);


		// Buttons
		$cmd = $this->absence_reason->getId() ? ilAttendanceListConfigGUI::CMD_UPDATE_REASON : ilAttendanceListConfigGUI::CMD_CREATE_REASON;

		$this->addCommandButton($cmd,$this->lng->txt('save'));
		$this->addCommandButton(ilAttendanceListConfigGUI::CMD_SHOW_REASONS,$this->lng->txt('cancel'));
	}


	/**
	 *
	 */
	public function fillForm() {
		$values = array(
			'title' => $this->absence_reason->getTitle(),
			'info' => $this->absence_reason->getInfo(),
			'comment_req' => $this->absence_reason->getCommentReq(),
			'upload_req' => $this->absence_reason->getUploadReq()
		);
		$this->setValuesByArray($values);
	}


	/**
	 * @return bool
	 */
	public function saveObject() {
		if (!$this->checkInput()) {
			return false;
		}
		$this->absence_reason->setTitle($this->getInput('title'));
		$this->absence_reason->setInfo($this->getInput('info'));
		$this->absence_reason->setCommentReq($this->getInput('comment_req'));
		$this->absence_reason->setUploadReq($this->getInput('upload_req'));

		$this->absence_reason->store();

		return true;
	}


}