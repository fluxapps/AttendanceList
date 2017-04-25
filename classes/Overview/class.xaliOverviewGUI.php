<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/classes/class.xaliGUI.php';
require_once 'Services/UIComponent/Button/classes/class.ilLinkButton.php';
require_once 'Services/Utilities/classes/class.ilConfirmationGUI.php';
require_once 'Services/Form/classes/class.ilPropertyFormGUI.php';
require_once 'class.xaliOverviewUserTableGUI.php';
require_once 'class.xaliOverviewListTableGUI.php';

/**
 * Class xaliOverviewGUI
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xaliOverviewGUI extends xaliGUI {

	const CMD_STANDARD = 'showUsersOverview';
	const CMD_LISTS = 'showListsOverview';
	const CMD_EDIT_LIST = 'editList';
	const CMD_CONFIRM_DELETE_LISTS = 'confirmDeleteLists';
	const CMD_ADD_LIST = 'addList';
	const CMD_CREATE_LIST = 'createList';
	const CMD_APPLY_FILTER_USERS = 'applyFilterUsers';
	const CMD_RESET_FILTER_USERS = 'resetFilterUsers';
	const CMD_APPLY_FILTER_LISTS = 'applyFilterLists';
	const CMD_RESET_FILTER_LISTS = 'resetFilterLists';

	const SUBTAB_USERS = 'subtab_users';
	const SUBTAB_LISTS = 'subtab_lists';


	/**
	 *
	 */
	public function showUsersOverview() {
		$this->setSubtabs(self::SUBTAB_USERS);
		$users = $this->parent_gui->getMembers();
		$xaliOverviewUserTableGUI = new xaliOverviewUserTableGUI($this, $users, $this->parent_gui->obj_id);
		$this->tpl->setContent($xaliOverviewUserTableGUI->getHTML());
	}


	/**
	 *
	 */
	public function showListsOverview() {
		global $ilToolbar;
		$this->setSubtabs(self::SUBTAB_LISTS);
		/** @var ilToolbarGUI $ilToolbarGUI*/
		$add_button = ilLinkButton::getInstance();
		$add_button->setPrimary(true);
		$add_button->setCaption($this->pl->txt('button_add_list'), false);
		$add_button->setUrl($this->ctrl->getLinkTarget($this, self::CMD_ADD_LIST));
		$ilToolbar->addButtonInstance($add_button);
		$xaliOverviewListTableGUI = new xaliOverviewListTableGUI($this, $this->parent_gui->obj_id);
		$this->tpl->setContent($xaliOverviewListTableGUI->getHTML());
	}


	public function applyFilterUsers() {
		$users = $this->parent_gui->getMembers();
		$xaliOverviewUserTableGUI = new xaliOverviewUserTableGUI($this, $users, $this->parent_gui->obj_id);
		$xaliOverviewUserTableGUI->writeFilterToSession();
		$xaliOverviewUserTableGUI->resetOffset();
		$this->ctrl->redirect($this, self::CMD_STANDARD);
	}

	public function resetFilterUsers() {
		$users = $this->parent_gui->getMembers();
		$xaliOverviewUserTableGUI = new xaliOverviewUserTableGUI($this, $users, $this->parent_gui->obj_id);
		$xaliOverviewUserTableGUI->resetFilter();
		$xaliOverviewUserTableGUI->resetOffset();
		$this->ctrl->redirect($this, self::CMD_STANDARD);
	}

	public function applyFilterLists() {
		$xaliOverviewUserTableGUI = new xaliOverviewListTableGUI($this, $this->parent_gui->obj_id);
		$xaliOverviewUserTableGUI->writeFilterToSession();
		$xaliOverviewUserTableGUI->resetOffset();
		$this->ctrl->redirect($this, self::CMD_LISTS);
	}

	public function resetFilterLists() {
		$xaliOverviewUserTableGUI = new xaliOverviewListTableGUI($this, $this->parent_gui->obj_id);
		$xaliOverviewUserTableGUI->resetFilter();
		$xaliOverviewUserTableGUI->resetOffset();
		$this->ctrl->redirect($this, self::CMD_LISTS);
	}

	/**
	 * @param $active
	 */
	public function setSubtabs($active) {
		$this->tabs->addSubTab(self::SUBTAB_USERS, $this->pl->txt(self::SUBTAB_USERS), $this->ctrl->getLinkTarget($this, self::CMD_STANDARD));
		$this->tabs->addSubTab(self::SUBTAB_LISTS, $this->pl->txt(self::SUBTAB_LISTS), $this->ctrl->getLinkTarget($this, self::CMD_LISTS));
		$this->tabs->setSubTabActive($active);
	}


	/**
	 *
	 */
	public function save() {
		if (count($this->parent_gui->getMembers()) != count($_POST['attendance_status'])) {
			ilUtil::sendFailure($this->pl->txt('warning_list_incomplete'), true);
			$this->editList();
			return;
		}

		if ($checklist_id = $_GET['checklist_id']) {
			$checklist = xaliChecklist::find($checklist_id);
		} else {
			$checklist = new xaliChecklist();
			$checklist->setObjId($this->parent_gui->obj_id);
		}

		$checklist->setLastEditedBy($this->user->getId());
		$checklist->setLastUpdate(time());
		$checklist->store();

		foreach ($_POST['attendance_status'] as $usr_id => $status) {
			$entry = $checklist->getEntryOfUser($usr_id);
			$entry->setChecklistId($checklist->getId());
			$entry->setStatus($status);
			$entry->setUserId($usr_id);
			$entry->store();
		}

		ilUtil::sendSuccess($this->pl->txt('msg_checklist_saved'), true);
		$this->ctrl->redirect($this, self::CMD_LISTS);
	}


	/**
	 *
	 */
	public function addList() {
		$form = new ilPropertyFormGUI();

		$date_input = new ilDateTimeInputGUI($this->pl->txt('form_input_date'), 'checklist_date');
		$form->addItem($date_input);

		$form->addCommandButton(self::CMD_CREATE_LIST, $this->lng->txt('create'));
		$form->addCommandButton(self::CMD_CANCEL, $this->lng->txt('cancel'));

		$form->setFormAction($this->ctrl->getFormAction($this));

		$this->tpl->setContent($form->getHTML());
		return;
	}


	/**
	 *
	 */
	public function createList() {
		$date = $_POST['checklist_date'];
		$date = $date['date']['y'] . '-' . $date['date']['m'] . '-' . $date['date']['d'];
		$this->checkDate($date);
		$checklist = new xaliChecklist();
		$checklist->setObjId($this->parent_gui->obj_id);
		$checklist->setChecklistDate($date);
		$checklist->setLastEditedBy($this->user->getId());
		$checklist->setLastUpdate(time());
		$checklist->create();
		ilUtil::sendSuccess($this->pl->txt('msg_list_created'), true);
		$this->ctrl->setParameter($this, 'checklist_id', $checklist->getId());
		$this->ctrl->redirect($this, self::CMD_EDIT_LIST);
	}


	/**
	 * @param $date
	 */
	protected function checkDate($date) {
		$where = xaliChecklist::where(array('checklist_date' => $date, 'obj_id' => $this->parent_gui->obj_id));
		if ($where->hasSets()) {
			ilUtil::sendFailure(sprintf($this->pl->txt('msg_date_already_used'), $date), true);
			$this->ctrl->redirect($this, self::CMD_ADD_LIST);
		}
	}


	/**
	 *
	 */
	public function editList() {
		$this->ctrl->saveParameter($this, 'checklist_id');
		$users = $this->parent_gui->getMembers();
		$checklist = xaliChecklist::find($_GET['checklist_id']);

		$xaliChecklistTableGUI = new xaliChecklistTableGUI($this, $checklist, $users);
		$xaliChecklistTableGUI->setTitle(sprintf($this->pl->txt('table_checklist_title'), $checklist->getChecklistDate()));

		$this->tpl->setContent($xaliChecklistTableGUI->getHTML());
	}


	/**
	 *
	 */
	public function confirmDeleteLists() {
		$conf = new ilConfirmationGUI();
		$conf->setFormAction($this->ctrl->getFormAction($this));
		$conf->setHeaderText($this->pl->txt('msg_confirm_delete_list'));
		$conf->setConfirm($this->lng->txt('delete'), 'deleteLists');
		$conf->setCancel($this->lng->txt('cancel'), 'cancel');

		$checklist_ids = $_GET['checklist_id'] ? array($_GET['checklist_id']) : $_POST['checklist_ids'];
		foreach ($checklist_ids as $id) {
			$checklist = xaliChecklist::find($id);
			$conf->addItem('checklist_id[]', $checklist->getId(), sprintf($this->pl->txt('table_checklist_title'), $checklist->getChecklistDate()));
		}
		$this->tpl->setContent($conf->getHTML());
	}


	/**
	 *
	 */
	public function deleteLists() {
		$checklist_ids = is_array($_POST['checklist_id']) ? $_POST['checklist_id'] : array($_POST['checklist_id']);
		foreach ($checklist_ids as $id) {
			$checklist = xaliChecklist::find($id);
			$checklist->delete();
		}
		ilUtil::sendSuccess($this->pl->txt('msg_list_deleted'), true);
		$this->ctrl->redirect($this, self::CMD_LISTS);
	}


	/**
	 *
	 */
	protected function cancel() {
		$this->ctrl->redirect($this, self::CMD_LISTS);
	}
}