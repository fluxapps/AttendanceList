<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once 'Services/Table/classes/class.ilTable2GUI.php';

/**
 * Class xaliUserDetailsTableGUI
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xaliUserDetailsTableGUI extends ilTable2GUI {

	/**
	 * @var ilObjUser
	 */
	protected $user;
	/**
	 * @var string
	 */
	protected $obj_id;
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
	 * @var xaliOverviewGUI
	 */
	protected $parent_obj;


	/**
	 * xaliUserDetailsTableGUI constructor.
	 *
	 * @param        $a_parent_obj
	 * @param string $user_id
	 */
	public function __construct(xaliOverviewGUI $a_parent_obj, $user_id, $obj_id) {
		global $ilCtrl, $lng;
		$this->ctrl = $ilCtrl;
		$this->lng = $lng;
		$this->pl = ilAttendanceListPlugin::getInstance();
		$this->user = new ilObjUser($user_id);
		$this->obj_id = $obj_id;

		parent::__construct($a_parent_obj);

		$this->setTitle($this->user->getFirstname() . ' ' . $this->user->getLastname());

		$this->setExportFormats(array(self::EXPORT_CSV, self::EXPORT_EXCEL));

		$this->setEnableNumInfo(false);
		$this->setRowTemplate('tpl.user_details_row.html', 'Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList');
		$this->ctrl->setParameter($this->parent_obj, 'user_id', $this->user->getId());
		$this->setFormAction($this->ctrl->getFormAction($a_parent_obj));
		$this->setLimit(0);
		$this->resetOffset();
		$this->initColumns();

		$this->initCommands();

		$this->parseData();
	}


	/**
	 *
	 */
	protected function initCommands() {
		$this->addCommandButton('saveUsers', $this->lng->txt('save'));
		$this->addCommandButton('cancel', $this->lng->txt('cancel'));
	}


	/**
	 *
	 */
	protected function initColumns() {
		$this->addColumn($this->pl->txt('table_column_date'));
		$this->addColumn($this->pl->txt('table_column_tutor'));
		$this->addColumn($this->pl->txt('table_column_status'));
	}

	/**
	 *  parse user ids to data for the table
	 */
	protected function parseData() {
		$data = array();
		/** @var xaliChecklist $checklist */
		foreach (xaliChecklist::where(array('obj_id' => $this->obj_id))->orderBy('checklist_date')->get() as $checklist) {
			$checklist_data = array();
			$checklist_data["id"] = $checklist->getId();
			$checklist_data["date"] = $checklist->getChecklistDate();
			$checklist_data["tutor"] = $checklist->getLastEditedBy(true);

			$checklist_entry = $checklist->getEntryOfUser($this->user->getId());
			if ($status = $checklist_entry->getStatus()) {
				$checklist_data["checked_$status"] = 'checked';
			} else {
				$checklist_data["checked_" . xaliChecklistEntry::STATUS_PRESENT] = 'checked';
				$checklist_data["warning"] = $this->pl->txt('warning_not_filled_out');
			}

			$data[] = $checklist_data;
		}
		ksort($data);
		$this->setData($data);
	}

	/**
	 * @param array $a_set
	 */
	protected function fillRow($a_set) {
		parent::fillRow($a_set);

		$this->ctrl->setParameter($this->parent_obj, 'checklist_id', $a_set['id']);
		$this->tpl->setVariable('VAL_EDIT_LINK', $this->ctrl->getLinkTarget($this->parent_obj, 'editList'));

		//		foreach (array('unexcused', 'excused', 'present') as $label) {
		foreach (array('unexcused', 'present') as $label) {
			$this->tpl->setVariable('LABEL_'.strtoupper($label), $this->pl->txt('label_'.$label));
		}
	}


	/**
	 * @param object $a_csv
	 * @param array  $a_set
	 */
	public function fillRowCSV($a_csv, $a_set) {
		unset($a_set['id']);
		foreach ($a_set as $key => $value)
		{
			if ($value == 'checked') {
				$status_id = substr($key, -1);
				$value = $this->pl->txt('status_' . $status_id);
			}
			if(is_array($value))
			{
				$value = implode(', ', $value);
			}
			$a_csv->addColumn(strip_tags($value));
		}
		$a_csv->addRow();
	}


	/**
	 * @param object $a_worksheet
	 * @param int    $a_row
	 * @param array  $a_set
	 */
	protected function fillRowExcel($a_worksheet, &$a_row, $a_set) {
		unset($a_set['id']);
		$col = 0;
		foreach ($a_set as $key => $value)
		{
			if ($value == 'checked') {
				$status_id = substr($key, -1);
				$value = $this->pl->txt('status_' . $status_id);
			}
			if(is_array($value))
			{
				$value = implode(', ', $value);
			}
			$a_worksheet->write($a_row, $col, strip_tags($value));
			$col++;
		}
	}
}