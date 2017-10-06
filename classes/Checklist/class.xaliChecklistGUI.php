<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once 'class.xaliChecklistTableGUI.php';
require_once 'class.xaliChecklist.php';
require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/classes/class.xaliGUI.php';
require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/classes/UserStatus/class.xaliUserStatus.php';
require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/classes/Settings/class.xaliSetting.php';
/**
 * Class xaliChecklistGUI
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xaliChecklistGUI extends xaliGUI {

	/**
	 * @var xaliChecklist
	 */
	protected $checklist;
	/**
	 * @var ilObjUser
	 */
	protected $user;
	/**
	 * @var xaliSetting
	 */
	protected $settings;

	/**
	 * xaliChecklistGUI constructor.
	 *
	 * @param ilObjAttendanceListGUI $parent_gui
	 */
	public function __construct(ilObjAttendanceListGUI $parent_gui) {
		parent::__construct($parent_gui);

		$this->settings = xaliSetting::find($parent_gui->obj_id);

		$list_query = xaliChecklist::where(array('checklist_date' => date('Y-m-d'), 'obj_id' => $parent_gui->obj_id));
		if ($list_query->hasSets()) {
			$this->checklist = $list_query->first();
		} else {
			$this->checklist = new xaliChecklist();
			$this->checklist->setChecklistDate(date('Y-m-d'));
			$this->checklist->setObjId($parent_gui->obj_id);
		}
	}


	/**
	 * standard command
	 */
	public function show() {
		if ((time()-(60*60*24)) > strtotime($this->settings->getActivationTo())) {
			ilUtil::sendInfo($this->pl->txt('activation_passed'), true);
			return;
		}

		if (!$this->checklist->isComplete()) {
			ilUtil::sendInfo($this->pl->txt('list_unsaved_today'), true);
		}
		$users = $this->parent_gui->getMembers();

		$xaliChecklistTableGUI = new xaliChecklistTableGUI($this, $this->checklist, $users);
		$xaliChecklistTableGUI->setTitle(sprintf($this->pl->txt('table_checklist_title'), $this->checklist->getChecklistDate()));

		$this->tpl->setContent($xaliChecklistTableGUI->getHTML());
	}


	/**
	 *
	 */
	public function saveList() {
		if (count($this->parent_gui->getMembers()) != count($_POST['attendance_status'])) {
			ilUtil::sendFailure($this->pl->txt('warning_list_incomplete'), true);
			$this->show();
			return;
		}

		$this->checklist->setLastEditedBy($this->user->getId());
		$this->checklist->setLastUpdate(time());
		$this->checklist->store();

		foreach ($_POST['attendance_status'] as $usr_id => $status) {
			$entry = $this->checklist->getEntryOfUser($usr_id);
			$entry->setChecklistId($this->checklist->getId());
			$entry->setStatus($status);
			$entry->setUserId($usr_id);
			$entry->store();
		}

		// update LP
		xaliUserStatus::updateUserStatuses($this->parent_gui->obj_id);

		ilUtil::sendSuccess($this->pl->txt('msg_checklist_saved'), true);
		$this->ctrl->redirect($this, self::CMD_STANDARD);
	}

}