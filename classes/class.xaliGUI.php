<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class xaliGUI
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xaliGUI {

	const CMD_STANDARD = 'show';
	const CMD_CANCEL = 'cancel';

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
	 * @var ilObjAttendanceListGUI
	 */
	protected $parent_gui;
	/**
	 * @var ilTabsGUI
	 */
	protected $tabs;
	/**
	 * @var ilObjUser
	 */
	protected $user;
	/**
	 * @var ilToolbarGUI
	 */
	protected $toolbar;


	/**
	 * xaliGUI constructor.
	 *
	 * @param ilObjAttendanceListGUI $parent_gui
	 */
	function __construct(ilObjAttendanceListGUI $parent_gui) {
		global $tpl, $ilCtrl, $ilTabs, $lng, $ilUser, $ilToolbar;
		$this->toolbar = $ilToolbar;
		$this->user = $ilUser;
		$this->lng = $lng;
		$this->tabs = $ilTabs;
		$this->tpl = $tpl;
		$this->ctrl = $ilCtrl;
		$this->pl = ilAttendanceListPlugin::getInstance();
		$this->parent_gui = $parent_gui;
	}


	/**
	 *
	 */
	public function executeCommand() {
		$this->prepareOutput();
		if (ilObjAttendanceListAccess::hasWriteAccess()) {
			$this->parent_gui->checkPassedIncompleteLists();
		}

		$nextClass = $this->ctrl->getNextClass();

		switch ($nextClass) {
			default:
				$cmd = $this->ctrl->getCmd(static::CMD_STANDARD);
				$this->{$cmd}();
				break;
		}
	}


	/**
	 *
	 */
	protected function prepareOutput() { }


	/**
	 *
	 */
	protected function cancel() {
		$this->ctrl->redirect($this, static::CMD_STANDARD);
	}
}