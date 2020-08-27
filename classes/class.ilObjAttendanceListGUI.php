<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once __DIR__ . '/../vendor/autoload.php';
use srag\DIC\AttendanceList\DICTrait;
/**
 * Class ilObjAttendanceListGUI
 *
 * @ilCtrl_isCalledBy   ilObjAttendanceListGUI: ilRepositoryGUI, ilObjPluginDispatchGUI, ilAdministrationGUI
 * @ilCtrl_Calls        ilObjAttendanceListGUI: xaliChecklistGUI, xaliSettingsGUI, xaliOverviewGUI
 * @ilCtrl_Calls        ilObjAttendanceListGUI: xaliAbsenceStatementGUI
 * @ilCtrl_Calls        ilObjAttendanceListGUI: ilInfoScreenGUI, ilPermissionGUI, ilCommonActionDispatcherGUI
 *
 * @author              Theodor Truffer <tt@studer-raimann.ch>
 */
class ilObjAttendanceListGUI extends ilObjectPluginGUI {
    use DICTrait;
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
	 * @var ilObjUser
	 */
	protected $user;
	/**
	 * @var xaliSetting
	 */
	protected $setting;


	/**
	 *
	 */
	protected function afterConstructor() {
		global $DIC;
		$tpl = $DIC['tpl'];
		$ilCtrl = $DIC['ilCtrl'];
		$ilTabs = $DIC['ilTabs'];
		$tree = $DIC['tree'];
		$rbacreview = $DIC['rbacreview'];
		$lng = $DIC['lng'];
		$ilUser = $DIC['ilUser'];

		$this->lng = $lng;
		$this->tpl = $tpl;
		$this->ctrl = $ilCtrl;
		$this->tabs = $ilTabs;
		$this->access = new ilObjAttendanceListAccess();
		$this->pl = ilAttendanceListPlugin::getInstance();
		$this->tree = $tree;
		$this->user = $ilUser;
		$this->rbacreview = $rbacreview;
	}


	/**
	 * @return string
	 */
	function getType() {
		return ilAttendanceListPlugin::PLUGIN_ID;
	}


	/**
	 *
	 */
	protected function initHeaderAndLocator() {
		global $DIC;
		$ilNavigationHistory = $DIC['ilNavigationHistory'];

		// get standard template (includes main menu and general layout)
        if (self::version()->is6()) {
            $this->tpl->loadStandardTemplate();
        } else {
		$this->tpl->getStandardTemplate();
		}
		$this->setTitleAndDescription();
		// set title
		if (!$this->getCreationMode()) {
			$this->tpl->setTitle($this->object->getTitle());
			$this->tpl->setTitleIcon(ilObject::_getIcon($this->object->getId()));

			//			$list_gui = ilObjectListGUIFactory::_getListGUIByType('xali');
			//			$this->tpl->setAlertProperties($list_gui->getAlertProperties());
			// set tabs
			if (strtolower($_GET['baseClass']) != 'iladministrationgui') {
				if (strtolower($_GET['cmdClass']) != 'xaliabsencestatementgui') {
					$this->setTabs();
				}
				$this->setLocator();
			} else {
				$this->addAdminLocatorItems();
				$this->tpl->setLocator();
				$this->setAdminTabs();
			}

			global $DIC;
			$ilAccess = $DIC['ilAccess'];
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
			case 'xaliabsencestatementgui':
				if (xaliChecklistEntry::find($_GET['entry_id'])->getUserId() != $this->user->getId()) {
					$this->checkPermission("write");
				}
				$xaliAbsenceStatementGUI = new xaliAbsenceStatementGUI($this);
				//				$this->tabs->setTabActive(self::TAB_SETTINGS);
				$this->ctrl->forwardCommand($xaliAbsenceStatementGUI);
				break;
			case 'ilpermissiongui':
				$this->checkPermission("edit_permission");
				$perm_gui = new ilPermissionGUI($this);
				$this->tabs->setTabActive("id_permissions");
				$this->ctrl->forwardCommand($perm_gui);
				break;
			default:
				$this->$cmd();
				break;
		}
		if ($cmd != 'create') {
            if (self::version()->is6()) {
                $this->tpl->printToStdout();
            } else {
			$this->tpl->show();
			}
		}
	}


	/**
	 * @param $a_target
	 */
	public static function _goto($a_target) {
		global $DIC;

		$ilCtrl = $DIC->ctrl();
		$ilAccess = $DIC->access();
		$lng = $DIC->language();

		$t = explode("_", $a_target[0]);
		$ref_id = (int) $t[0];
		$class_name = $a_target[1];

		if (count($t) == 2) {
			$entry_id = $t[1];
//			$_GET['entry_id'] = $entry_id;
			$ilCtrl->initBaseClass("ilObjPluginDispatchGUI");
			$ilCtrl->setTargetScript("ilias.php");
			$ilCtrl->getCallStructure(strtolower("ilObjPluginDispatchGUI"));
			$ilCtrl->setParameterByClass($class_name, "ref_id", $ref_id);
			$ilCtrl->setParameterByClass($class_name, "entry_id", $entry_id);
			$ilCtrl->redirectByClass(array("ilobjplugindispatchgui", self::class, xaliAbsenceStatementGUI::class), xaliAbsenceStatementGUI::CMD_STANDARD);
		}


		if ($ilAccess->checkAccess("read", "", $ref_id))
		{
			$ilCtrl->initBaseClass("ilObjPluginDispatchGUI");
			$ilCtrl->setTargetScript("ilias.php");
			$ilCtrl->getCallStructure(strtolower("ilObjPluginDispatchGUI"));
			$ilCtrl->setParameterByClass($class_name, "ref_id", $ref_id);
			$ilCtrl->redirectByClass(array("ilobjplugindispatchgui", $class_name), "");
		}
		else if($ilAccess->checkAccess("visible", "", $ref_id))
		{
			$ilCtrl->initBaseClass("ilObjPluginDispatchGUI");
			$ilCtrl->setTargetScript("ilias.php");
			$ilCtrl->getCallStructure(strtolower("ilObjPluginDispatchGUI"));
			$ilCtrl->setParameterByClass($class_name, "ref_id", $ref_id);
			$ilCtrl->redirectByClass(array("ilobjplugindispatchgui", $class_name), "infoScreen");
		}
		else if ($ilAccess->checkAccess("read", "", ROOT_FOLDER_ID))
		{
			ilUtil::sendFailure(sprintf($lng->txt("msg_no_perm_read_item"),
				ilObject::_lookupTitle(ilObject::_lookupObjId($ref_id))));
			ilObjectGUI::_gotoRepositoryRoot();
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
		$this->tabs->addTab(self::TAB_CONTENT, $this->pl->txt(self::TAB_CONTENT), $this->ctrl->getLinkTargetByClass(xaliChecklistGUI::class, xaliChecklistGUI::CMD_STANDARD));
		$this->addInfoTab();
		if ($this->access->hasWriteAccess()) {
			$this->tabs->addTab(self::TAB_OVERVIEW, $this->pl->txt(self::TAB_OVERVIEW), $this->ctrl->getLinkTargetByClass(xaliOverviewGUI::class, xaliOverviewGUI::CMD_STANDARD));
			$this->tabs->addTab(self::TAB_SETTINGS, $this->pl->txt(self::TAB_SETTINGS), $this->ctrl->getLinkTargetByClass(xaliSettingsGUI::class, xaliSettingsGUI::CMD_STANDARD));
		}
		parent::setTabs();
	}


	/**
	 *
	 */
	public function showContent() {
		$this->ctrl->redirectByClass(xaliChecklistGUI::class, xaliChecklistGUI::CMD_STANDARD);
	}


	/**
	 *
	 */
	public function showOverview() {
		$this->ctrl->redirectByClass(xaliOverviewGUI::class, xaliOverviewGUI::CMD_STANDARD);
	}


	/**
	 *
	 */
	public function editSettings() {
		$this->ctrl->redirectByClass(xaliSettingsGUI::class, xaliSettingsGUI::CMD_STANDARD);
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
			$this->ctrl->redirectByClass(ilRepositoryGUI::class);
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

		$form->setPreventDoubleSubmission(false);

		return $form;
	}


	public function save() {
		$form = $this->initCreateForm($this->getType());
		$form->setValuesByPost();
		$form->checkInput();

		$this->setting = new xaliSetting();
		$this->setting->setActivation(true);

		$from = $form->getInput(xaliSettingsFormGUI::F_ACTIVATION_FROM);
		$this->setting->setActivationFrom($from);

		$to = $form->getInput(xaliSettingsFormGUI::F_ACTIVATION_TO);
		$this->setting->setActivationTo($to);

		$this->setting->setActivationWeekdays($form->getInput(xaliSettingsFormGUI::F_WEEKDAYS));

		$this->saveObject();
	}


	/**
	 * @param ilObject $newObj
	 */
	function afterSave(ilObject $newObj) {
		$this->setting->setId($newObj->getId());
		$this->setting->create();
		$this->setting->createOrDeleteEmptyLists(true, false);

		parent::afterSave($newObj);
	}


	/**
	 * @return bool
	 */
	public function checkPassedIncompleteLists() {
		$members_count = count($this->getMembers());
		foreach (xaliChecklist::where(array( 'obj_id' => $this->obj_id ))->get() as $checklist) {
			if (date('Y-m-d') > $checklist->getChecklistDate()
				&& ($checklist->getEntriesCount() < $members_count)) {
				$link_to_overview = $this->ctrl->getLinkTargetByClass(xaliOverviewGUI::class, xaliOverviewGUI::CMD_LISTS);
				ilUtil::sendInfo(sprintf($this->pl->txt('msg_incomplete_lists'), $link_to_overview), true);

				return true;
			}
		}

		return false;
	}


	public function getParentCourseOrGroupId($ref_id) {
		global $DIC;
		$tree = $DIC['tree'];
		while (!in_array(ilObject2::_lookupType($ref_id, true), array( 'crs', 'grp' ))) {
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
		return $this->pl->getMembers($this->object->ref_id);
	}
}
