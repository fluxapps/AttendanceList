<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once __DIR__ . '/../vendor/autoload.php';
/**
 * Class ilAttendanceListPlugin
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy ilAttendanceListPlugin: ilUIPluginRouterGUI
 */
class ilAttendanceListPlugin extends ilRepositoryObjectPlugin {

	const PLUGIN_NAME = 'AttendanceList';

	/**
	 * @var ilAttendanceListPlugin
	 */
	protected static $instance;


	/**
	 *
	 */
	public function executeCommand() {
		global $ilCtrl;
		$cmd = $ilCtrl->getCmd();
		switch($cmd) {
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
	 *
	 */
	protected function uninstallCustom() {
		// TODO: Implement uninstallCustom() method.
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
		include_once './Services/User/classes/class.ilUserAutoComplete.php';
		$auto = new ilUserAutoComplete();
		$auto->setSearchFields(array('login','firstname','lastname'));
		$auto->setResultField('login');
		$auto->enableFieldSearchableCheck(false);
		$auto->setMoreLinkAvailable(true);


		if(($_REQUEST['fetchall']))
		{
			$auto->setLimit(ilUserAutoComplete::MAX_ENTRIES);
		}

		$list = $auto->getList($_REQUEST['term']);


		$array = json_decode($list, true);
		$members = $this->getMembers();

		foreach($array['items'] as $key => $item) {
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
		global $rbacreview;
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
		global $tree, $ilLog;
		while (!in_array(ilObject2::_lookupType($ref_id, true), array('crs', 'grp'))) {
			$ilLog->write($ref_id);
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
		global $tree;
		$attendancelist = array_shift($tree->getChildsByType($crs_ref_id, $this->getId()));
		$ref_id = $attendancelist['child'];
		if ($get_ref_id) {
			return $ref_id;
		}
		return ilObjAttendanceList::_lookupObjectId($ref_id);
	}
}