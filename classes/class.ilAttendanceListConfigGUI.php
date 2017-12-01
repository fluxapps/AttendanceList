<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once __DIR__ . '/../vendor/autoload.php';
/**
 * Class ilAttendanceListConfigGUI
 *
 * @ilCtrl_IsCalledBy  ilAttendanceListConfigGUI: ilObjComponentSettingsGUIs
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ilAttendanceListConfigGUI extends ilPluginConfigGUI {

	const CMD_STANDARD = 'configure';
	const CMD_ADD_REASON = 'addReason';
	const CMD_CREATE_REASON = 'createReason';
	const CMD_EDIT_REASON = 'editReason';
	const CMD_UPDATE_REASON = 'updateReason';
	const CMD_DELETE_REASON = 'deleteReason';

	/**
	 * @var ilTemplate
	 */
	protected $tpl;
	/**
	 * @var ilCtrl
	 */
	protected $ctrl;
	/**
	 * @var ilAttendanceListPlugin
	 */
	protected $pl;
	/**
	 * @var ilToolbarGUI
	 */
	protected $toolbar;

	/**
	 * ilAttendanceListConfigGUI constructor.
	 */
	public function __construct() {
		global $tpl, $ilCtrl, $ilToolbar;
		$this->ctrl = $ilCtrl;
		$this->tpl = $tpl;
		$this->toolbar = $ilToolbar;
		$this->pl = ilAttendanceListPlugin::getInstance();
	}


	function performCommand($cmd) {
		switch ($cmd) {
			case self::CMD_STANDARD:
				$this->addToolbarButton();
				break;
		}
		$this->{$cmd}();
	}

	protected function configure() {
		$xaliConfigAbsencesTableGUI = new xaliConfigAbsencesTableGUI($this);
		$this->tpl->setContent($xaliConfigAbsencesTableGUI->getHTML());
	}

	protected function addReason() {
		$xaliConfigAbsenceFormGUI = new xaliConfigAbsenceFormGUI($this, new xaliAbsenceReason());
		$this->tpl->setContent($xaliConfigAbsenceFormGUI->getHTML());
	}

	protected function createReason() {
		$xaliConfigAbsenceFormGUI = new xaliConfigAbsenceFormGUI($this, new xaliAbsenceReason());
		$xaliConfigAbsenceFormGUI->setValuesByPost();
		if ($xaliConfigAbsenceFormGUI->saveObject()) {
			ilUtil::sendSuccess($this->pl->txt('msg_saved'), true);
			$this->ctrl->redirect($this, self::CMD_STANDARD);
		}
		$this->tpl->setContent($xaliConfigAbsenceFormGUI->getHTML());
	}

	protected function editReason() {
		$xaliConfigAbsenceFormGUI = new xaliConfigAbsenceFormGUI($this, new xaliAbsenceReason($_GET['ar_id']));
		$xaliConfigAbsenceFormGUI->fillForm();
		$this->tpl->setContent($xaliConfigAbsenceFormGUI->getHTML());
	}

	/**
	 *
	 */
	protected function updateReason() {
		$xaliConfigAbsenceFormGUI = new xaliConfigAbsenceFormGUI($this, new xaliAbsenceReason($_GET['ar_id']));
		$xaliConfigAbsenceFormGUI->setValuesByPost();
		if ($xaliConfigAbsenceFormGUI->saveObject()) {
			ilUtil::sendSuccess($this->pl->txt('msg_saved'), true);
			$this->ctrl->redirect($this, self::CMD_STANDARD);
		}
		$this->tpl->setContent($xaliConfigAbsenceFormGUI->getHTML());
	}


	protected function addToolbarButton() {
		$button = ilLinkButton::getInstance();
		$button->setUrl($this->ctrl->getLinkTarget($this, self::CMD_ADD_REASON));
		$button->setCaption($this->pl->txt('config_add_new_absence_reason'), false);
		$this->toolbar->addButtonInstance($button);
	}
}