<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Class ilObjAttendanceListAccess
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ilObjAttendanceListAccess extends ilObjectPluginAccess {

	/**
	 * @param string $a_cmd
	 * @param string $a_permission
	 * @param int    $a_ref_id
	 * @param int    $a_obj_id
	 * @param string $a_user_id
	 *
	 * @return bool
	 */
	public function _checkAccess($a_cmd, $a_permission, $a_ref_id, $a_obj_id = NULL, $a_user_id = ''): bool
    {
		global $DIC;
		$ilUser = $DIC['ilUser'];
		$ilAccess = $DIC['ilAccess'];
		/**
		 * @var $ilAccess ilAccessHandler
		 */
		if ($a_user_id == '') {
			$a_user_id = $ilUser->getId();
		}
		if ($a_obj_id === NULL) {
			$a_obj_id = ilObject2::_lookupObjId($a_ref_id);
		}

		switch ($a_permission) {
			case 'read':
			case 'visible':
				if ((!ilObjAttendanceListAccess::checkOnline($a_obj_id) OR !ilObjAttendanceListAccess::checkActivation($a_obj_id))
					AND !$ilAccess->checkAccessOfUser($a_user_id, 'write', '', $a_ref_id)) {
					return false;
				}
				break;
		}

		return true;
	}


	/**
	 * @param        $a_permission
	 * @param        $a_cmd
	 * @param        $a_ref_id
	 * @param string $a_type
	 * @param string $a_obj_id
	 * @param string $a_tree_id
	 *
	 * @return mixed
	 */
	function checkAccess($a_permission, $a_cmd, $a_ref_id, string $a_type = "", string $a_obj_id = "", string $a_tree_id = ""): mixed
    {
		return $this->access->checkAccess($a_permission, $a_cmd, $a_ref_id, $a_type, $a_obj_id, $a_tree_id);
	}


	/**
	 * @param $a_id
	 *
	 * @return bool
	 */
	static function checkOnline($a_id): bool
    {
        //todo
        return true;

		/**
		 * @var $xaliSettings xaliSetting
		 */
		$xaliSettings = xaliSetting::findOrGetInstance($a_id);

		return (bool)$xaliSettings->getIsOnline();
	}


	/**
	 * @return bool
	 */
	static function checkActivation($a_id): bool
    {
        //todo
        return true;

		/** @var xaliSetting $settings */
		$settings = xaliSetting::find($a_id);
		$today = date('Y-m-d');

		return !$settings->getActivation() || (($today >= $settings->getActivationFrom()) && ($today <= $settings->getActivationTo()));
	}


	/**
	 * @param null $ref_id
	 * @param null $user_id
	 *
	 * @return bool
	 */
	public static function hasReadAccess($ref_id = NULL, $user_id = NULL): bool
    {
		return self::hasAccess('read', $ref_id, $user_id);
	}


	/**
	 * @param null $ref_id
	 * @param null $user_id
	 *
	 * @return bool
	 */
	public static function hasWriteAccess($ref_id = NULL, $user_id = NULL): bool
    {
		return self::hasAccess('write', $ref_id, $user_id);
	}


	/**
	 * @param      $permission
	 * @param null $ref_id
	 * @param null $user_id
	 *
	 * @return bool
	 */
	protected static function hasAccess($permission, $ref_id = NULL, $user_id = NULL): bool
    {
		global $DIC;
		$ilUser = $DIC['ilUser'];
		$ilAccess = $DIC['ilAccess'];
		$ilLog = $DIC['ilLog'];
		/**
		 * @var $ilAccess \ilAccessHandler
		 */
		$ref_id = $ref_id ? $ref_id : $_GET['ref_id'];
		$user_id = $user_id ? $user_id : $ilUser->getId();

		return $ilAccess->checkAccessOfUser($user_id, $permission, '', $ref_id);
	}

	/**
	 * check whether goto script will succeed
	 */
	static function _checkGoto($a_target): bool
    {
		global $DIC;

		$ilAccess = $DIC->access();

		$t_arr = explode("_", $a_target);
		if (count($t_arr) == 3) { // access to absence statement -> access will be checked later
			return true;
		}

		if ($ilAccess->checkAccess("read", "", $t_arr[1]))
		{
			return true;
		}
		return false;
	}
}