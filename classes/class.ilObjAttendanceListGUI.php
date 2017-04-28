<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */

require_once ('./Services/Repository/classes/class.ilObjectPluginGUI.php');
require_once ('./Services/Object/classes/class.ilObjectFactory.php');
//require_once ('./Services/Object/classes/class.ilObject2.php');
require_once ('class.ilAttendanceListPlugin.php');
require_once ('class.ilObjAttendanceListAccess.php');
require_once ('class.ilObjAttendanceListAccess.php');
require_once ('Checklist/class.xaliChecklistGUI.php');
require_once ('Settings/class.xaliSettingsGUI.php');
require_once ('Settings/class.xaliSetting.php');
require_once ('Overview/class.xaliOverviewGUI.php');
/**
 * Class ilObjAttendanceListGUI
 *
 * @ilCtrl_isCalledBy   ilObjAttendanceListGUI: ilRepositoryGUI, ilObjPluginDispatchGUI, ilAdministrationGUI
 * @ilCtrl_Calls        ilObjAttendanceListGUI: xaliChecklistGUI, xaliSettingsGUI, xaliOverviewGUI
 * @ilCtrl_Calls        ilObjAttendanceListGUI: ilInfoScreenGUI, ilPermissionGUI, ilCommonActionDispatcherGUI
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ilObjAttendanceListGUI extends ilObjectPluginGUI {

	const XALIST = 'xali';

	const CMD_STANDARD = 'showContent';
	const CMD_OVERVIEW = 'showOverview';
	const CMD_EDIT_SETTINGS = 'editSettings';

	const TAB_CONTENT = 'tab_content';
	const TAB_OVERVIEW = 'tab_overview';
	const TAB_SETTINGS = 'tab_settings';
	/**
	 * @var ilCtrl
	 */
	protected $ctrl;
	/**
	 * @var ilTabsGUI
	 */
	protected $tabs;
	/**
	 * @var ilAttendanceListPlugin
	 */
	protected $pl;
	/**
	 * @var ilObjAttendanceListAccess
	 */
	protected $access;
	/**
	 * @var ilRbacReview
	 */
	protected $rbacreview;


	/**
	 *
	 */
	protected function afterConstructor() {
		global $tpl, $ilCtrl, $ilTabs, $tree, $rbacreview, $lng;

		$this->lng = $lng;
		$this->tpl = $tpl;
		$this->ctrl = $ilCtrl;
		$this->tabs = $ilTabs;
		$this->access = new ilObjAttendanceListAccess();
		$this->pl = ilAttendanceListPlugin::getInstance();
		$this->tree = $tree;
		$this->rbacreview = $rbacreview;
	}


	/**
	 * @return string
	 */
	function getType() {
		return self::XALIST;
	}


	/**
	 *
	 */
	protected function initHeaderAndLocator() {
		global $ilNavigationHistory;

		// get standard template (includes main menu and general layout)
		$this->tpl->getStandardTemplate();
		$this->setTitleAndDescription();
		// set title
		if (!$this->getCreationMode()) {
			$this->tpl->setTitle($this->object->getTitle());
			$this->tpl->setTitleIcon(ilObject::_getIcon($this->object->getId()));

//			$list_gui = ilObjectListGUIFactory::_getListGUIByType('xali');
//			$this->tpl->setAlertProperties($list_gui->getAlertProperties());
			// set tabs
			if (strtolower($_GET['baseClass']) != 'iladministrationgui') {
				$this->setTabs();
				$this->setLocator();
			} else {
				$this->addAdminLocatorItems();
				$this->tpl->setLocator();
				$this->setAdminTabs();
			}

			global $ilAccess;
			// add entry to navigation history
			if ($ilAccess->checkAccess('read', '', $_GET['ref_id'])) {
				$ilNavigationHistory->addItem($_GET['ref_id'], $this->ctrl->getLinkTarget($this, $this->getStandardCmd()), $this->getType());
			}
		} else {
			// show info of parent
			$this->tpl->setTitle(ilObject::_lookupTitle(ilObject::_lookupObjId($_GET['ref_id'])));
			$this->tpl->setTitleIcon(ilObject::_getIcon(ilObject::_lookupObjId($_GET['ref_id']), 'big'), $this->pl->txt('obj_'
				. ilObject::_lookupType($_GET['ref_id'], true)));
			$this->setLocator();
		}
	}


	/**
	 *
	 */
	function &executeCommand() {
		$this->initHeaderAndLocator();

		$cmd = $this->ctrl->getCmd(self::CMD_STANDARD);


		$next_class = $this->ctrl->getNextClass($this);

		switch ($next_class) {
			case 'xalichecklistgui':
				$this->checkPermission("read");
				$xaliChecklistGUI = new xaliChecklistGUI($this);
				$this->tabs->setTabActive(self::TAB_CONTENT);
				$this->ctrl->forwardCommand($xaliChecklistGUI);
				break;
			case "ilinfoscreengui":
				// cmd here is showSummary
				$this->checkPermission("visible");
				parent::infoScreen();    // forwards command
				break;
			case 'xalioverviewgui':
				$this->checkPermission("write");
				$xaliOverviewGUI = new xaliOverviewGUI($this);
				$this->tabs->setTabActive(self::TAB_OVERVIEW);
				$this->ctrl->forwardCommand($xaliOverviewGUI);
				break;
			case 'xalisettingsgui':
				$this->checkPermission("write");
				$xaliSettingsGUI = new xaliSettingsGUI($this);
				$this->tabs->setTabActive(self::TAB_SETTINGS);
				$this->ctrl->forwardCommand($xaliSettingsGUI);
				break;
			case 'ilpermissiongui':
				$this->checkPermission("edit_permission");
				include_once("Services/AccessControl/classes/class.ilPermissionGUI.php");
				$perm_gui = new ilPermissionGUI($this);
				$this->tabs->setTabActive("id_permissions");
				$this->ctrl->forwardCommand($perm_gui);
				break;
			default:
				$this->$cmd();
				break;
		}
		if ($cmd != 'create') {
			$this->tpl->show();
		}
	}

	/**
	 * show information screen
	 */
	function infoScreen() {
		$this->ctrl->setCmd('showSummary');
		$this->ctrl->setCmdClass("ilinfoscreengui");
		parent::infoScreen();
	}


	/**
	 *
	 */
	protected function setTabs() {
		$this->tabs->addTab(self::TAB_CONTENT, $this->pl->txt(self::TAB_CONTENT), $this->ctrl->getLinkTargetByClass('xaliChecklistGUI', xaliChecklistGUI::CMD_STANDARD));
		$this->addInfoTab();
		if ($this->access->hasWriteAccess()) {
			$this->tabs->addTab(self::TAB_OVERVIEW, $this->pl->txt(self::TAB_OVERVIEW), $this->ctrl->getLinkTargetByClass('xaliOverviewGUI', xaliOverviewGUI::CMD_STANDARD));
			$this->tabs->addTab(self::TAB_SETTINGS, $this->pl->txt(self::TAB_SETTINGS), $this->ctrl->getLinkTargetByClass('xaliSettingsGUI', xaliSettingsGUI::CMD_STANDARD));
		}
		parent::setTabs();
	}


	/**
	 *
	 */
	public function showContent() {
		$this->ctrl->redirectByClass('xaliChecklistGUI', xaliChecklistGUI::CMD_STANDARD);
	}


	/**
	 *
	 */
	public function showOverview() {
		$this->ctrl->redirectByClass('xaliOverviewGUI', xaliOverviewGUI::CMD_STANDARD);
	}

	/**
	 *
	 */
	public function editSettings() {
		$this->ctrl->redirectByClass('xaliSettingsGUI', xaliSettingsGUI::CMD_STANDARD);
	}


	/**
	 * @return string
	 */
	function getAfterCreationCmd() {
		return self::CMD_EDIT_SETTINGS;
	}


	/**
	 * @return string
	 */
	function getStandardCmd() {
		return self::CMD_STANDARD;
	}


	/**
	 * @param string $a_new_type
	 *
	 * @return array
	 */
	protected function initCreationForms($a_new_type) {
		try {
			$this->getParentCourseOrGroupId($_GET['ref_id']);
		} catch (Exception $e) {
			ilUtil::sendFailure($this->pl->txt('msg_creation_failed'), true);
			$this->ctrl->redirectByClass('ilRepositoryGUI');
		}

		$forms = array(
			self::CFORM_NEW => $this->initCreateForm($a_new_type),
		);

		return $forms;
	}


	public function initCreateForm($a_new_type) {
		$form = parent::initCreateForm($a_new_type);

		$from = new ilDateTimeInputGUI($this->pl->txt(xaliSettingsFormGUI::F_ACTIVATION_FROM), xaliSettingsFormGUI::F_ACTIVATION_FROM);
		$form->addItem($from);

		$to = new ilDateTimeInputGUI($this->pl->txt(xaliSettingsFormGUI::F_ACTIVATION_TO), xaliSettingsFormGUI::F_ACTIVATION_TO);
		$form->addItem($to);

		$wd = new srWeekdayInputGUI($this->pl->txt(xaliSettingsFormGUI::F_WEEKDAYS), xaliSettingsFormGUI::F_WEEKDAYS);
		$form->addItem($wd);

		return $form;
	}


	public function save() {
		$form = $this->initCreateForm($this->getType());
		$form->setValuesByPost();
		$form->checkInput();

		$xaliSetting = new xaliSetting();
		$xaliSetting->setActivation(true);

		$from = $form->getInput(xaliSettingsFormGUI::F_ACTIVATION_FROM);
		$xaliSetting->setActivationFrom($from['date']);

		$to = $form->getInput(xaliSettingsFormGUI::F_ACTIVATION_TO);
		$xaliSetting->setActivationTo($to['date']);

		$xaliSetting->setActivationWeekdays($form->getInput(xaliSettingsFormGUI::F_WEEKDAYS));

		$this->saveObject($xaliSetting);
	}


	/**
	 * @param ilObject $newObj
	 */
	function afterSave($newObj, $additional_args) {
		$xaliSetting = $additional_args[0];

		$xaliSetting->setId($newObj->getId());
		$xaliSetting->create();
		$xaliSetting->createEmptyLists(true, false);

		parent::afterSave($newObj);
	}


	/**
	 * @return bool
	 */
	public function checkPassedIncompleteLists() {
		$members_count = count($this->getMembers());
		foreach (xaliChecklist::where(array('obj_id' => $this->obj_id))->get() as $checklist) {
			if ( date('Y-m-d') > $checklist->getChecklistDate()
				&& ($checklist->getEntriesCount() < $members_count)) {
				$link_to_overview = $this->ctrl->getLinkTargetByClass('xaliOverviewGUI', xaliOverviewGUI::CMD_LISTS);
				ilUtil::sendInfo(sprintf($this->pl->txt('msg_incomplete_lists'), $link_to_overview), true);
				return true;
			}
		}
		return false;
	}

	public function getParentCourseOrGroupId($ref_id) {
		global $tree;
		while (!in_array(ilObject2::_lookupType($ref_id, true), array('crs', 'grp'))) {
			if ($ref_id == 1) {
				throw new Exception("Parent of ref id {$ref_id} is neither course nor group.");
			}
			$ref_id = $tree->getParentId($ref_id);
		}
		return $ref_id;
	}

	/**
	 * @return array
	 */
	public function getMembers() {
		return $this->object->getMembers();
	}


}
