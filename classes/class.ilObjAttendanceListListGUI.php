<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once __DIR__ . '/../vendor/autoload.php';
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
		$this->timings_enabled = false;
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
				'cmd' => ilObjAttendanceListGUI::CMD_EDIT_SETTINGS,
				'lang_var' => 'edit'
			)
		);

		return $commands;
	}

	/**
	 * Get item properties
	 *
	 * @return    array        array of property arrays:
	 *                        'alert' (boolean) => display as an alert property (usually in red)
	 *                        'property' (string) => property name
	 *                        'value' (string) => property value
	 */
	public function getCustomProperties() {

		$props = parent::getCustomProperties(array());

		try {
			/** @var xaliSetting $settings */
			$settings = xaliSetting::find($this->obj_id);
			if ($settings != null) {
				if ($settings->getActivation()) {
					$activation_from = date('d. M Y', strtotime($settings->getActivationFrom()));
					$activation_to = date('d. M Y', strtotime($settings->getActivationTo()));
					$props[] = array(
						'alert' => false,
						'newline' => true,
						'property' => $this->lng->txt('activation'),
						'value' => $activation_from . ' - ' . $activation_to,
						'propertyNameVisible' => true
					);
				}
				if (!$settings->getIsOnline()) {
					$props[] = array(
						'alert' => true,
						'newline' => true,
						'property' => 'Status',
						'value' => 'Offline',
						'propertyNameVisible' => true
					);
				}
			}
		} catch (Exception $e) {

		}


		return $props;
	}

	/**
	 * get all alert properties
	 *
	 * @return array
	 */
	public function getAlertProperties() {
		$alert = array();
		foreach ((array)$this->getCustomProperties() as $prop) {
			if ($prop['alert'] == true) {
				$alert[] = $prop;
			}
		}

		return $alert;
	}

	function initType() {
		$this->setType('xali');
	}
}