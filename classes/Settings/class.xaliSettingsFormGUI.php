<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
/**
 * Class xaliSettingsFormGUI
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xaliSettingsFormGUI extends ilPropertyFormGUI {

	const F_TITLE = 'title';
	const F_DESCRIPTION = 'description';
	const F_ONLINE = 'online';
	const F_MINIMUM_ATTENDANCE = 'minimum_attendance';
	const F_ACTIVATION = 'activation';
	const F_ACTIVATION_FROM = 'activation_from';
	const F_ACTIVATION_TO = 'activation_to';
	const F_ACTIVATION_WEEKDAYS = 'activation_weekdays';
	const F_CREATE_LISTS = 'create_lists';
	const F_DELETE_LISTS = 'delete_lists';
	const F_WEEKDAYS = 'weekdays';

	/**
	 * @var xaliSettingsGUI
	 */
	protected $parent_gui;
	/**
	 * @var ilCtrl
	 */
	protected $ctrl;
	/**
	 * @var ilAttendanceListPlugin
	 */
	protected $pl;
	/**
	 * @var ilLanguage
	 */
	protected $lng;
	/**
	 * @var xaliSetting
	 */
	protected $settings;
	/**
	 * @var ilObjAttendanceList
	 */
	protected $object;

	/**
	 * xaliSettingsFormGUI constructor.
	 *
	 * @param $parent_gui xaliSettingsGUI
	 */
	public function __construct(xaliSettingsGUI $parent_gui, ilObjAttendanceList $object) {
		global $ilCtrl, $lng, $tpl;
		$this->parent_gui = $parent_gui;
		$this->ctrl = $ilCtrl;
		$this->pl = ilAttendanceListPlugin::getInstance();
		$this->lng = $lng;
		$this->object = $object;
		$this->settings = xaliSetting::find($object->getId());
		$this->setFormAction($this->ctrl->getFormAction($parent_gui));
		$this->initForm();
	}


	/**
	 *
	 */
	public function initForm() {
		$input = new ilTextInputGUI($this->lng->txt(self::F_TITLE), self::F_TITLE);
		$input->setRequired(true);
		$input->setValue($this->object->getTitle());
		$this->addItem($input);

		$input = new ilTextInputGUI($this->lng->txt(self::F_DESCRIPTION), self::F_DESCRIPTION);
		$input->setValue($this->object->getDescription());
		$this->addItem($input);

		$input = new ilCheckboxInputGUI($this->lng->txt(self::F_ONLINE), self::F_ONLINE);
		$input->setChecked($this->settings->getIsOnline());
		$this->addItem($input);

		$input = new ilSelectInputGUI($this->pl->txt(self::F_MINIMUM_ATTENDANCE), self::F_MINIMUM_ATTENDANCE);
		$options = array();
		for ($i = 0; $i <= 100; $i++) {
			$options[$i] = $i . '%';
		}
		$input->setOptions($options);
		$input->setValue($this->settings->getMinimumAttendance());
		$input->setInfo($this->pl->txt(self::F_MINIMUM_ATTENDANCE . '_info'));
		$this->addItem($input);

		$input = new ilDateTimeInputGUI($this->pl->txt(self::F_ACTIVATION_FROM), self::F_ACTIVATION_FROM);
		$input->setValueByArray(array(self::F_ACTIVATION_FROM => array("date" => $this->settings->getActivationFrom())));
		$this->addItem($input);

		$input = new ilDateTimeInputGUI($this->pl->txt(self::F_ACTIVATION_TO), self::F_ACTIVATION_TO);
		$input->setValueByArray(array(self::F_ACTIVATION_TO => array("date" => $this->settings->getActivationTo())));
		$this->addItem($input);

		$input = new srWeekdayInputGUI($this->pl->txt(self::F_WEEKDAYS), self::F_WEEKDAYS);
		$input->setValue($this->settings->getActivationWeekdays());
		$this->addItem($input);

		$input = new ilCheckboxInputGUI($this->pl->txt(self::F_CREATE_LISTS), self::F_CREATE_LISTS);
		$input->setInfo($this->pl->txt(self::F_CREATE_LISTS . '_info'));
		$this->addItem($input);

		$input = new ilCheckboxInputGUI($this->pl->txt(self::F_DELETE_LISTS), self::F_DELETE_LISTS);
		$input->setInfo($this->pl->txt(self::F_DELETE_LISTS . '_info'));
		$this->addItem($input);

		$this->addCommandButton(xaliSettingsGUI::CMD_SAVE, $this->lng->txt('save'));
	}


	/**
	 * @return bool
	 */
	public function saveSettings() {
		if (!$this->checkInput()) {
			return false;
		}

		$this->object->setTitle($this->getInput(self::F_TITLE));
		$this->object->setDescription($this->getInput(self::F_DESCRIPTION));
		$this->object->update();

		$this->settings->setIsOnline($this->getInput(self::F_ONLINE));
		$this->settings->setMinimumAttendance($this->getInput(self::F_MINIMUM_ATTENDANCE));
		$this->settings->setActivation($this->getInput(self::F_ACTIVATION));

		$activation_from = $this->getInput(self::F_ACTIVATION_FROM);
		$this->settings->setActivationFrom($activation_from['date']);

		$activation_to = $this->getInput(self::F_ACTIVATION_TO);
		$this->settings->setActivationTo($activation_to['date']);

		$this->settings->setActivationWeekdays($this->getInput(self::F_WEEKDAYS));

		$this->settings->update();

		if ($this->getInput(self::F_CREATE_LISTS) || $this->getInput(self::F_DELETE_LISTS)) {
			$this->settings->createOrDeleteEmptyLists($this->getInput(self::F_CREATE_LISTS), $this->getInput(self::F_DELETE_LISTS));
		}

		return true;
	}
}