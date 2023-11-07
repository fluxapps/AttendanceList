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
	protected xaliSettingsGUI $parent_gui;
	protected ilCtrl $ctrl;
	protected ilAttendanceListPlugin $pl;
	protected ilLanguage $lng;
	protected ?xaliSetting $xaliSetting;
	protected ilObjAttendanceList $object;

    /**
     * xaliSettingsFormGUI constructor.
     *
     * @param $parent_gui xaliSettingsGUI
     * @throws ilCtrlException
     */
	public function __construct(xaliSettingsGUI $parent_gui, ilObjAttendanceList|ilObject $object) {
		global $DIC;
		$ilCtrl = $DIC['ilCtrl'];
		$lng = $DIC['lng'];
		$tpl = $DIC['tpl'];
        if (isset($DIC["http"])) {
            $this->http = $DIC->http();
        }
		$this->parent_gui = $parent_gui;
		$this->ctrl = $ilCtrl;
		$this->pl = ilAttendanceListPlugin::getInstance();
		$this->lng = $lng;
		$this->object = $object;
		$this->xaliSetting = xaliSetting::find($object->getId());
		$this->setFormAction($this->ctrl->getFormAction($parent_gui));
		$this->initForm();
	}


	/**
	 *
	 */
	public function initForm(): void
    {
		$input = new ilTextInputGUI($this->lng->txt(self::F_TITLE), self::F_TITLE);
		$input->setRequired(true);
		$input->setValue($this->object->getTitle());
		$this->addItem($input);

		$input = new ilTextInputGUI($this->lng->txt(self::F_DESCRIPTION), self::F_DESCRIPTION);
		$input->setValue($this->object->getDescription());
		$this->addItem($input);

		$input = new ilCheckboxInputGUI($this->lng->txt(self::F_ONLINE), self::F_ONLINE);
		$input->setChecked($this->xaliSetting->getIsOnline());
		$this->addItem($input);

		$input = new ilSelectInputGUI($this->pl->txt(self::F_MINIMUM_ATTENDANCE), self::F_MINIMUM_ATTENDANCE);
		$options = array();
        $options[xaliSetting::CALC_AUTO_MINIMUM_ATTENDANCE] = $this->pl->txt(self::F_MINIMUM_ATTENDANCE . '_auto');
		for ($i = 0; $i <= 100; $i++) {
			$options[$i] = $i . '%';
		}
		$input->setOptions($options);
		$input->setValue($this->xaliSetting->getMinimumAttendance());
		$input->setInfo($this->pl->txt(self::F_MINIMUM_ATTENDANCE . '_info'));
		$this->addItem($input);

		$input = new ilDateTimeInputGUI($this->pl->txt(self::F_ACTIVATION_FROM), self::F_ACTIVATION_FROM);
		$input->setValueByArray(array(self::F_ACTIVATION_FROM => $this->xaliSetting->getActivationFrom()));
		$this->addItem($input);

		$input = new ilDateTimeInputGUI($this->pl->txt(self::F_ACTIVATION_TO), self::F_ACTIVATION_TO);
		$input->setValueByArray(array(self::F_ACTIVATION_TO => $this->xaliSetting->getActivationTo()));
		$this->addItem($input);

		$input = new srWeekdayInputGUI($this->pl->txt(self::F_WEEKDAYS), self::F_WEEKDAYS);
		$input->setValue($this->xaliSetting->getActivationWeekdays());
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
     * @throws Exception
     */
    public function saveSettings(): bool
    {
		if (!$this->checkInput()) {
			return false;
		}

		$this->object->setTitle($this->getInput(self::F_TITLE));
		$this->object->setDescription($this->getInput(self::F_DESCRIPTION));
		$this->object->update();

		$this->xaliSetting->setIsOnline((int) $this->getInput(self::F_ONLINE));
		$this->xaliSetting->setMinimumAttendance($this->getInput(self::F_MINIMUM_ATTENDANCE));
		$this->xaliSetting->setActivation((int)$this->getInput(self::F_ACTIVATION));

		$activation_from = $this->getInput(self::F_ACTIVATION_FROM);
		$this->xaliSetting->setActivationFrom($activation_from);

		$activation_to = $this->getInput(self::F_ACTIVATION_TO);
		$this->xaliSetting->setActivationTo($activation_to);

		$this->xaliSetting->setActivationWeekdays($this->getInput(self::F_WEEKDAYS));

		$this->xaliSetting->update();

		if ($this->getInput(self::F_CREATE_LISTS) || $this->getInput(self::F_DELETE_LISTS)) {
			$this->xaliSetting->createOrDeleteEmptyLists($this->getInput(self::F_CREATE_LISTS), $this->getInput(self::F_DELETE_LISTS));
		}

		return true;
	}
}