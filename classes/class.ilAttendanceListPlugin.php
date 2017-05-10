<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once ('./Services/Repository/classes/class.ilRepositoryObjectPlugin.php');
require_once ('./Services/Object/classes/class.ilObjectFactory.php');
require_once ('./Services/Object/classes/class.ilObject2.php');
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

	function getPluginName() {
		return self::PLUGIN_NAME;
	}

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
		static $parent;
		if (!$parent) {
			$ref_id = $ref_id ? $ref_id : $_GET['ref_id'];
			$parent = ilObjectFactory::getInstanceByRefId($this->getParentCourseOrGroupId($ref_id));
		}

		return $parent;
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

}