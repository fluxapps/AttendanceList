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
	/**
	 * @var bool
	 */
	protected $has_passed_students = false;


	/**
	 * xaliOverviewUserTableGUI constructor.
	 *
	 * @param xaliOverviewGUI $a_parent_obj
	 * @param array           $users
	 * @param string          $obj_id
	 */
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


	/**
	 *
	 */
	protected function parseData() {
		$data = array();
		if ($this->filter['user_id']) {

			foreach ($this->users as $usr_id) {
				$user = new ilObjUser($usr_id);
				$user_data = array();
				if ($this->filter['user_id'] != 'all' && $this->filter['user_id'] != $user->getId()) {
					continue;
				}
				$user_data["name"] = $user->getFullname();
				$user_data["login"] = $user->getLogin();
				$user_data["id"] = $user->getId();

				/** @var xaliUserStatus $xaliUserStatus */
				$xaliUserStatus = xaliUserStatus::getInstance($user->getId(), $this->obj_id);

				$user_data["present"] = $xaliUserStatus->getAttendanceStatuses(xaliChecklistEntry::STATUS_PRESENT);
				$user_data["excused"] = $xaliUserStatus->getAttendanceStatuses(xaliChecklistEntry::STATUS_ABSENT_EXCUSED);
				$user_data["unexcused"] = $xaliUserStatus->getAttendanceStatuses(xaliChecklistEntry::STATUS_ABSENT_UNEXCUSED);

				$user_data['reached_percentage'] = $xaliUserStatus->getReachedPercentage();

				$user_data['no_status'] = $xaliUserStatus->getUnedited();
				$user_data['percentage'] = $user_data['reached_percentage'] . '% / ' . $this->settings->getMinimumAttendance() . '%';
				$data[] = $user_data;
			}
		}
		$this->setData($data);
	}


	/**
	 * @param array $a_set
	 */
	protected function fillRow($a_set) {
		parent::fillRow($a_set);
		$color = ($a_set['reached_percentage'] < $this->settings->getMinimumAttendance()) ? 'red' : 'green';
		if ($color == 'green') {
			$this->has_passed_students = true;
		}
		$this->tpl->setVariable('COLOR', $color);
	}


	/**
	 * @return array
	 */
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


	/**
	 *
	 */
	protected function initColumns() {
		$this->addColumn($this->pl->txt('table_column_name'), 'name');
		$this->addColumn($this->pl->txt('table_column_login'), 'login');
		$this->addColumn($this->pl->txt('table_column_present'), 'present');
		$this->addColumn($this->pl->txt('table_column_excused'), 'excused');
		$this->addColumn($this->pl->txt('table_column_unexcused'), 'unexcused');
		$this->addColumn($this->pl->txt('table_column_no_status'), 'no_status');
		$this->addColumn($this->pl->txt('table_column_percentage'), 'reached_percentage');
	}


	/**
	 * @param $a_field
	 *
	 * @return bool
	 */
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


	/**
	 * @param object $a_csv
	 * @param array  $a_set
	 */
	protected function fillRowCSV($a_csv, $a_set) {
		unset($a_set['id']);
		unset($a_set['reached_percentage']);
		parent::fillRowCSV($a_csv, $a_set);
	}


	/**
	 * @param object $a_worksheet
	 * @param int    $a_row
	 * @param array  $a_set
	 */
	protected function fillRowExcel($a_worksheet, &$a_row, $a_set) {
		unset($a_set['id']);
		unset($a_set['reached_percentage']);
		parent::fillRowExcel($a_worksheet, $a_row, $a_set);
	}


	/**
	 * @return boolean
	 */
	public function hasPassedStudents() {
		return $this->has_passed_students;
	}


}