<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/classes/class.xaliGUI.php';
require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/classes/Checklist/class.xaliChecklist.php';
require_once 'class.xaliSettingsFormGUI.php';
/**
 * Class xaliSettingsGUI
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xaliSettingsGUI extends xaliGUI {

	const CMD_STANDARD = 'showContent';
	const CMD_SAVE = 'save';

	public function showContent() {
		$xaliSettingsFormGUI = new xaliSettingsFormGUI($this, $this->parent_gui->object);
		$this->tpl->setContent($xaliSettingsFormGUI->getHTML());
	}

	public function save() {
		$xaliSettingsFormGUI = new xaliSettingsFormGUI($this, $this->parent_gui->object);
		$xaliSettingsFormGUI->setValuesByPost();
		if ($xaliSettingsFormGUI->saveSettings()) {
			ilUtil::sendSuccess($this->lng->txt('saved_successfully'), true);
			$this->ctrl->redirect($this, self::CMD_STANDARD);
			return;
		}
		$this->tpl->setContent($xaliSettingsFormGUI->getHTML());
	}

	public function createEmptyLists() {
		/** @var xaliSetting $settings */
		$settings = xaliSetting::find($this->parent_gui->obj_id);
		$begin = $settings->getActivationFrom();
		$end = $settings->getActivationTo();
		$weekdays = $settings->getActivationWeekdays();
		if (!$weekdays || empty($weekdays) || !$begin || !$end) {
			return false;
		}

		$begin = new DateTime($begin);
		$end = new DateTime($end);

		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($begin, $interval, $end);

		foreach ($period as $dt) {
			if (in_array($dt->format("D"), $weekdays)) {
				$where = xaliChecklist::where(array('checklist_date' => $dt->format('Y-m-d'), 'obj_id' => $this->parent_gui->obj_id));
				if (!$where->hasSets()) {
					$checklist = new xaliChecklist();
					$checklist->setChecklistDate($dt->format('Y-m-d'));
					$checklist->setObjId($this->parent_gui->obj_id);
					$checklist->create();
				}
			}
		}
	}
}