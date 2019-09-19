<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once __DIR__ . '/../vendor/autoload.php';

use srag\DIC\AttendanceList\DICTrait;
use srag\DIC\AttendanceList\Util\LibraryLanguageInstaller;
use srag\Plugins\AttendanceList\Notification\Notification\Language\NotificationLanguage;
use srag\Plugins\AttendanceList\Notification\Notification\Notification;

/**
 * Class ilAttendanceListPlugin
 *
 * @author            Theodor Truffer <tt@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy ilAttendanceListPlugin: ilUIPluginRouterGUI
 */
class ilAttendanceListPlugin extends ilRepositoryObjectPlugin {

	use DICTrait;
	const PLUGIN_ID = 'xali';
	const PLUGIN_NAME = 'AttendanceList';
	const PLUGIN_CLASS_NAME = self::class;
	const CMD_ADD_USER_AUTO_COMPLETE = 'addUserAutoComplete';
	/**
	 * @var ilAttendanceListPlugin
	 */
	protected static $instance;
	/**
	 * @var ilDB
	 */
	protected $db;


	function __construct() {
		parent::__construct();

		global $DIC;

		$this->db = $DIC->database();
	}


	/**
	 *
	 */
	public function executeCommand() {
		global $DIC;
		$ilCtrl = $DIC['ilCtrl'];
		$cmd = $ilCtrl->getCmd();
		switch ($cmd) {
			default:
				$this->{$cmd}();
				break;
		}
	}


	/**
	 * @return ilAttendanceListPlugin
	 */
	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * @return string
	 */
	function getPluginName() {
		return self::PLUGIN_NAME;
	}


	/**
	 * @inheritdoc
	 */
	public function updateLanguages($a_lang_keys = null) {
		parent::updateLanguages($a_lang_keys);

		LibraryLanguageInstaller::getInstance()->withPlugin(self::plugin())->withLibraryLanguageDirectory(__DIR__
			. "/../vendor/srag/notifications4plugin/lang")->updateLanguages();
	}


	/**
	 *
	 */
	protected function uninstallCustom() {
		$this->db->dropTable(xaliConfig::TABLE_NAME, false);
		$this->db->dropTable(xaliLastReminder::TABLE_NAME, false);
		$this->db->dropTable(xaliAbsenceReason::TABLE_NAME, false);
		$this->db->dropTable(xaliAbsenceStatement::TABLE_NAME, false);
		$this->db->dropTable(xaliSetting::DB_TABLE_NAME, false);
		$this->db->dropTable(xaliChecklist::DB_TABLE_NAME, false);
		$this->db->dropTable(xaliChecklistEntry::DB_TABLE_NAME, false);
		$this->db->dropTable(xaliUserStatus::TABLE_NAME, false);
		Notification::dropDB_();
		NotificationLanguage::dropDB_();

		return true;
	}


	/**
	 * Get ref id for object id.
	 * The ref id is unambiguous since there can't be references to attendance lists.
	 *
	 * @param $obj_id
	 *
	 * @return mixed
	 */
	static function lookupRefId($obj_id) {
		return array_shift(ilObject2::_getAllReferences($obj_id));
	}


	/**
	 * async auto complete method for user filter in overview
	 */
	public function addUserAutoComplete() {
		$auto = new ilUserAutoComplete();
		$auto->setSearchFields(array( 'login', 'firstname', 'lastname' ));
		$auto->setResultField('login');
		$auto->enableFieldSearchableCheck(false);
		$auto->setMoreLinkAvailable(true);

		if (($_REQUEST['fetchall'])) {
			$auto->setLimit(ilUserAutoComplete::MAX_ENTRIES);
		}

		$list = $auto->getList($_REQUEST['term']);

		$array = json_decode($list, true);
		$members = $this->getMembers();

		foreach ($array['items'] as $key => $item) {
			if (!in_array($item['id'], $members)) {
				unset($array['items'][$key]);
			}
		}

		$list = json_encode($array);
		echo $list;
		exit();
	}


	/**
	 * @return array
	 */
	public function getMembers($ref_id = 0) {
		global $DIC;
		$rbacreview = $DIC['rbacreview'];
		static $members;
		if (!$members) {
			$ref_id = $ref_id ? $ref_id : $_GET['ref_id'];
			$parent = $this->getParentCourseOrGroup($ref_id);
			$member_role = $parent->getDefaultMemberRole();
			$members = $rbacreview->assignedUsers($member_role);
		}

		return $members;
	}


	/**
	 * @return ilObjCourse|ilObjGroup
	 * @throws Exception
	 */
	public function getParentCourseOrGroup($ref_id = 0) {
		$ref_id = $ref_id ? $ref_id : $_GET['ref_id'];
		$parent = ilObjectFactory::getInstanceByRefId($this->getParentCourseOrGroupId($ref_id));

		return $parent;
	}


	/**
	 * @param $ref_id
	 *
	 * @return int
	 * @throws Exception
	 */
	public function getParentCourseOrGroupId($ref_id) {
		global $DIC;
		$tree = $DIC['tree'];
		$ilLog = $DIC['ilLog'];
		while (!in_array(ilObject2::_lookupType($ref_id, true), array( 'crs', 'grp' ))) {
			if ($ref_id == 1 || !$ref_id) {
				throw new Exception("Parent of ref id {$ref_id} is neither course nor group.");
			}
			$ref_id = $tree->getParentId($ref_id);
		}

		return $ref_id;
	}


	/**
	 * @param $user_id
	 * @param $crs_ref_id
	 *
	 * @return array
	 */
	public function getAttendancesForUserAndCourse($user_id, $crs_ref_id) {
		$obj_id = $this->getAttendanceListIdForCourse($crs_ref_id);
		$settings = new xaliSetting($obj_id);

		/** @var xaliUserStatus $xaliUserStatus */
		$xaliUserStatus = xaliUserStatus::getInstance($user_id, $obj_id);

		return array(
			'present' => $xaliUserStatus->getAttendanceStatuses(xaliChecklistEntry::STATUS_PRESENT),
			'absent' => $xaliUserStatus->getAttendanceStatuses(xaliChecklistEntry::STATUS_ABSENT_UNEXCUSED),
			'unedited' => $xaliUserStatus->getUnedited(),
			'percentage' => $xaliUserStatus->getReachedPercentage(),
			'minimum_attendance' => $obj_id ? $settings->getMinimumAttendance() : 0
		);
	}


	/**
	 * @param      $crs_ref_id
	 * @param bool $get_ref_id
	 *
	 * @return int
	 */
	public function getAttendanceListIdForCourse($crs_ref_id, $get_ref_id = false) {
		global $DIC;
		/** @var ilTree $tree */
		$tree = $DIC['tree'];
		$attendancelist = array_shift($tree->getSubTree($tree->getNodeData($crs_ref_id), true, $this->getId()));
		$ref_id = $attendancelist['child'];
		if ($get_ref_id) {
			return $ref_id;
		}

		return ilObjAttendanceList::_lookupObjectId($ref_id);
	}
}