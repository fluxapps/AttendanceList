<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once 'Services/Table/classes/class.ilTable2GUI.php';
/**
 * Class xaliOverviewUserTableGUI
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xaliOverviewUserTableGUI extends ilTable2GUI {

	/**
	 * @var ilAttendanceListPlugin
	 */
	protected $pl;
	/**
	 * @var array
	 */
	protected $users;
	/**
	 * @var int
	 */
	protected $obj_id;
	/**
	 * @var xaliSetting
	 */
	protected $settings;

	public function __construct(xaliOverviewGUI $a_parent_obj, array $users, $obj_id) {
		global $lng, $ilCtrl;
		$this->lng = $lng;
		$this->ctrl = $ilCtrl;
		$this->pl = ilAttendanceListPlugin::getInstance();
		$this->users = $users;
		$this->obj_id = $obj_id;
		$this->settings = xaliSetting::find($obj_id);

		parent::__construct($a_parent_obj);
		$this->setRowTemplate('tpl.user_overview_row.html', 'Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList');
		$this->setExportFormats(array(self::EXPORT_CSV, self::EXPORT_EXCEL));

		$this->setLimit(0);
		$this->initColumns();
		$this->initFilter();
		$this->setFormAction($ilCtrl->getFormAction($a_parent_obj, xaliOverviewGUI::CMD_APPLY_FILTER_USERS));

		$this->setDefaultOrderField('name');

		$this->setFilterCommand(xaliOverviewGUI::CMD_APPLY_FILTER_USERS);
		$this->setResetCommand(xaliOverviewGUI::CMD_RESET_FILTER_USERS);

		$this->parseData();
	}

	protected function parseData() {
		$data = array();
		if ($this->filter['user_id']) {
			$checklist_ids = $this->getChecklistIds();
			$operators = array(
				'user_id' => '=',
				'status' => '=',
				'checklist_id' => 'IN'
			);
			foreach ($this->users as $usr_id) {
				$user = new ilObjUser($usr_id);
				$user_data = array();
				if ($this->filter['user_id'] != 'all' && $this->filter['user_id'] != $user->getId()) {
					continue;
				}
				$user_data["name"] = $user->getFullname();
				$user_data["login"] = $user->getLogin();
				$user_data["id"] = $user->getId();

				if ($checklist_ids) {
					$user_data["present"] = xaliChecklistEntry::where(array(
						'user_id' => $usr_id,
						'status' => xaliChecklistEntry::STATUS_PRESENT,
						'checklist_id' => $checklist_ids
					), $operators)->count();
					$user_data["excused"] = xaliChecklistEntry::where(array(
						'user_id' => $usr_id,
						'status' => xaliChecklistEntry::STATUS_ABSENT_EXCUSED,
						'checklist_id' => $checklist_ids
					), $operators)->count();
					$user_data["unexcused"] = xaliChecklistEntry::where(array(
						'user_id' => $usr_id,
						'status' => xaliChecklistEntry::STATUS_ABSENT_UNEXCUSED,
						'checklist_id' => $checklist_ids
					), $operators)->count();

					$user_data['reached_percentage'] = round(($user_data["present"] + $user_data["excused"]) / count($checklist_ids) * 100);
				} else {
					$user_data['reached_percentage'] = $user_data["present"] = $user_data["excused"] = $user_data["unexcused"] = 0;
				}
				$user_data['no_status'] = count($checklist_ids) - ($user_data["present"] + $user_data["excused"] + $user_data["unexcused"]);
				$user_data['percentage'] = $user_data['reached_percentage'] . '% / ' . $this->settings->getMinimumAttendance() . '%';
				$data[] = $user_data;
			}
		}
		$this->setData($data);
	}


	protected function fillRow($a_set) {
		parent::fillRow($a_set);
		$color = ($a_set['reached_percentage'] < $this->settings->getMinimumAttendance()) ? 'red' : 'green';
		$this->tpl->setVariable('COLOR', $color);
	}


	protected function getChecklistIds() {
		$ids = array();
		foreach (xaliChecklist::where(array('obj_id' => $this->obj_id))->get() as $checklist) {
			$ids[] = $checklist->getId();
		}
		return $ids;
	}

	/**
	 *
	 */
	public function initFilter() {
		$user_filter = new ilSelectInputGUI($this->lng->txt('name'), 'name');
		$options = array();
		foreach ($this->users as $user_id) {
			$user = new ilObjUser($user_id);
			$options[$user_id] = $user->getFullname();
		}
		asort($options);
		$options = array(0 => $this->lng->txt('please_select'), 'all' => $this->pl->txt('all_users')) + $options;
		$user_filter->setOptions($options);
		$this->addFilterItem($user_filter);
		$user_filter->readFromSession();
		$this->filter['user_id'] = $user_filter->getValue();
	}

	protected function initColumns() {
		$this->addColumn($this->pl->txt('table_column_name'), 'name');
		$this->addColumn($this->pl->txt('table_column_login'), 'login');
		$this->addColumn($this->pl->txt('table_column_present'), 'present');
		$this->addColumn($this->pl->txt('table_column_excused'), 'excused');
		$this->addColumn($this->pl->txt('table_column_unexcused'), 'unexcused');
		$this->addColumn($this->pl->txt('table_column_no_status'), 'no_status');
		$this->addColumn($this->pl->txt('table_column_percentage'), 'reached_percentage');
	}


	function numericOrdering($a_field) {
		switch ($a_field) {
			case 'present':
			case 'excused':
			case 'unexcused':
			case 'no_status':
			case 'reached_percentage':
				return true;
			default:
				return false;
		}
	}


	protected function fillRowCSV($a_csv, $a_set) {
		unset($a_set['id']);
		unset($a_set['reached_percentage']);
		parent::fillRowCSV($a_csv, $a_set);
	}


	protected function fillRowExcel($a_worksheet, &$a_row, $a_set) {
		unset($a_set['id']);
		unset($a_set['reached_percentage']);
		parent::fillRowExcel($a_worksheet, $a_row, $a_set);
	}
}