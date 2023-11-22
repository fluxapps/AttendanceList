<?php
class xaliGUI {

	const CMD_STANDARD = 'show';
	const CMD_CANCEL = 'cancel';

	protected mixed $tpl;
	protected mixed $ctrl;
	protected ilAttendanceListPlugin|ilPlugin $pl;
	protected ilObjAttendanceListGUI $parent_gui;
	protected ilTabsGUI $tabs;
	protected mixed $user;
	protected ilToolbarGUI $toolbar;


	/**
	 * xaliGUI constructor.
	 *
	 * @param ilObjAttendanceListGUI $parent_gui
	 */
	function __construct(ilObjAttendanceListGUI $parent_gui) {
		global $DIC;
		$tpl = $DIC['tpl'];
		$ilCtrl = $DIC['ilCtrl'];
		$ilTabs = $DIC['ilTabs'];
		$lng = $DIC['lng'];
		$ilUser = $DIC['ilUser'];
		$ilToolbar = $DIC['ilToolbar'];
		$this->toolbar = $ilToolbar;
		$this->user = $ilUser;
		$this->lng = $lng;
		$this->tabs = $ilTabs;
		$this->tpl = $tpl;
		$this->ctrl = $ilCtrl;
        /** @var $component_factory ilComponentFactory */
        $component_factory = $DIC['component.factory'];
        /** @var $plugin ilAttendanceListPlugin */
        $this->pl  = $component_factory->getPlugin(ilAttendanceListPlugin::PLUGIN_ID);
		$this->parent_gui = $parent_gui;
	}


	/**
	 *
	 */
	public function executeCommand(): void
    {
		$this->prepareOutput();
		if (ilObjAttendanceListAccess::hasWriteAccess()) {
			$this->parent_gui->checkPassedIncompleteLists();
		}

		$nextClass = $this->ctrl->getNextClass();

		switch ($nextClass) {
			default:
				$cmd = $this->ctrl->getCmd(static::CMD_STANDARD);
				$this->{$cmd}();
				break;
		}
	}

	protected function prepareOutput() { }


	protected function cancel(): void
    {
		$this->ctrl->redirect($this, static::CMD_STANDARD);
	}
}