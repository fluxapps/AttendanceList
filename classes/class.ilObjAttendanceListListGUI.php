<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once ('./Services/Repository/classes/class.ilObjectPluginListGUI.php');
require_once ('./Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/classes/class.ilObjAttendanceListGUI.php');
/**
 * Class ilObjAttendanceListListGUI
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ilObjAttendanceListListGUI extends \ilObjectPluginListGUI {

	function getGuiClass() {
		return 'ilObjAttendanceListGUI';
	}


	function initCommands() {
		// Always set
		$this->timings_enabled = true;
		$this->subscribe_enabled = true;
		$this->payment_enabled = false;
		$this->link_enabled = false;
		$this->info_screen_enabled = true;
		$this->delete_enabled = true;
		$this->notes_enabled = true;
		$this->comments_enabled = true;

		// Should be overwritten according to status
		$this->cut_enabled = false;
		$this->copy_enabled = false;

		$commands = array(
			array(
				'permission' => 'read',
				'cmd' => ilObjAttendanceListGUI::CMD_STANDARD,
				'default' => true,
			),
			array(
				'permission' => 'write',
				'cmd' => ilObjAttendanceListGUI::CMD_STANDARD,
				'lang_var' => 'edit'
			)
		);

		return $commands;// TODO: Implement initCommands() method.
	}


	function initType() {
		$this->setType('xali');
	}
}