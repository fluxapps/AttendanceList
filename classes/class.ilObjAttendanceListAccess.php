<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once ('./Services/Repository/classes/class.ilObjectPluginAccess.php');
/**
 * Class ilObjAttendanceListAccess
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ilObjAttendanceListAccess extends \ilObjectPluginAccess {

	/**
	 * @param null $ref_id
	 * @param null $user_id
	 *
	 * @return bool
	 */
	public static function hasReadAccess($ref_id = NULL, $user_id = NULL) {
		return self::hasAccess('read', $ref_id, $user_id);
	}


	/**
	 * @param null $ref_id
	 * @param null $user_id
	 *
	 * @return bool
	 */
	public static function hasWriteAccess($ref_id = NULL, $user_id = NULL) {
		return self::hasAccess('write', $ref_id, $user_id);
	}

	/**
	 * @param      $permission
	 * @param null $ref_id
	 * @param null $user_id
	 *
	 * @return bool
	 */
	protected static function hasAccess($permission, $ref_id = NULL, $user_id = NULL) {
		global $ilUser, $ilAccess, $ilLog;
		/**
		 * @var $ilAccess \ilAccessHandler
		 */
		$ref_id = $ref_id ? $ref_id : $_GET['ref_id'];
		$user_id = $user_id ? $user_id : $ilUser->getId();

		return $ilAccess->checkAccessOfUser($user_id, $permission, '', $ref_id);
	}
}