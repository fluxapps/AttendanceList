<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once ('./Services/Repository/classes/class.ilRepositoryObjectPlugin.php');
require_once ('./Services/Object/classes/class.ilObject2.php');
/**
 * Class ilAttendanceListPlugin
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ilAttendanceListPlugin extends ilRepositoryObjectPlugin {

	const PLUGIN_NAME = 'AttendanceList';

	/**
	 * @var ilAttendanceListPlugin
	 */
	protected static $instance;


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

}