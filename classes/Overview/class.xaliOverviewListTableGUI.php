<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once 'Services/Table/classes/class.ilTable2GUI.php';
require_once 'Services/UIComponent/AdvancedSelectionList/classes/class.ilAdvancedSelectionListGUI.php';
/**
 * Class xaliOverviewListTableGUI
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xaliOverviewListTableGUI extends ilTable2GUI {

	/**
	 * @var ilAttendanceListPlugin
	 */
	protected $pl;
	/**
	 * @var int
	 */
	protected $obj_id;
	/**
	 * @var ilCtrl
	 */
	protected $ctrl;

	public function __construct(xaliOverviewGUI $a_parent_obj, $obj_id) {
		global $lng, $ilCtrl;
		$this->lng = $lng;
		$this->ctrl = $ilCtrl;
		$this->pl = ilAttendanceListPlugin::getInstance();
		$this->obj_id = $obj_id;

		parent::__construct($a_parent_obj, xaliOverviewGUI::CMD_LISTS);
		$this->setRowTemplate('tpl.list_overview_row.html', 'Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList');
		$this->setExportFormats(array(self::EXPORT_CSV, self::EXPORT_EXCEL));

		$this->initColumns();
		$this->initFilter();

		$this->setShowRowsSelector(true);
		$this->setFormAction($this->ctrl->getFormAction($a_parent_obj));

		$this->setFilterCommand(xaliOverviewGUI::CMD_APPLY_FILTER_LISTS);
		$this->setResetCommand(xaliOverviewGUI::CMD_RESET_FILTER_LISTS);

		$this->addMultiCommand(xaliOverviewGUI::CMD_CONFIRM_DELETE_LISTS, $this->lng->txt('delete'));

		$this->setDefaultOrderField('sort_date');

		$this->parseData();
	}

	public function parseData() {
		$data = array();
		foreach (xaliChecklist::where(array('obj_id' => $this->obj_id))->get() as $checklist) {
			/** @var $checklist xaliChecklist */
			$dataset = array();
			$dataset['id'] = $checklist->getId();
			$dataset['sort_date'] = $checklist->getChecklistDate();
			$dataset['date'] = date('D, d.m.Y', strtotime($checklist->getChecklistDate()));

			if ($date_filter = $this->filter['date']) {
				/** @var ilDateTime $from */
				if (isset($date_filter['from']) && $date_filter['from']->get(IL_CAL_DATE, 'Y-m-d') > $dataset['date']) {
					continue;
				}
				if (isset($date_filter['to']) && $date_filter['to']->get(IL_CAL_DATE, 'Y-m-d') < $dataset['date']) {
					continue;
				}
			}

			$tutor_id = $checklist->getLastEditedBy();
			if ($tutor_id) {
				$name = ilObjUser::_lookupName($tutor_id);
				$dataset['tutor'] = $name['firstname'] . ' ' . $name['lastname'];
			} else {
				$dataset['tutor'] = $this->pl->txt('automatically_created');
			}


			$present = $checklist->getStatusCount(xaliChecklistEntry::STATUS_PRESENT);
			$excused = $checklist->getStatusCount(xaliChecklistEntry::STATUS_ABSENT_EXCUSED);
			$unexcused = $checklist->getStatusCount(xaliChecklistEntry::STATUS_ABSENT_UNEXCUSED);
			$total = $present + $excused + $unexcused;

			$dataset['present'] = $total ? $present . ' (' . round($present / $total * 100) . '%)' : '-';
			$dataset['excused'] = $total ? $excused . ' (' . round($excused / $total * 100) . '%)' : '-';
			$dataset['unexcused'] = $total ? $unexcused . ' (' . round($unexcused / $total * 100) . '%)' : '-';
			$data[] = $dataset;
		}
		$this->setData($data);
	}

	public function fillRow($a_set) {
		parent::fillRow($a_set);
		$this->tpl->setVariable('ACTIONS', $this->buildAction($a_set));
	}

	public function buildAction($a_set) {
		$actions = new ilAdvancedSelectionListGUI();
		$actions->setListTitle($this->lng->txt('actions'));
		$this->ctrl->setParameter($this->parent_obj, 'checklist_id', $a_set['id']);
		$actions->addItem($this->lng->txt('edit'), 'edit', $this->ctrl->getLinkTarget($this->parent_obj, xaliOverviewGUI::CMD_EDIT_LIST));
		$actions->addItem($this->lng->txt('delete'), 'delete', $this->ctrl->getLinkTarget($this->parent_obj, xaliOverviewGUI::CMD_CONFIRM_DELETE_LISTS));
		return $actions->getHTML();
	}

	/**
	 *
	 */
	public function initFilter() {
		$date_filter = $this->addFilterItemByMetaType('date', self::FILTER_DATE_RANGE);
		$date_filter->readFromSession();
		$this->filter['date'] = $date_filter->getDate();
	}

	protected function initColumns() {
		$this->addColumn("", "", "1", true);
		$this->addColumn($this->pl->txt('table_column_date'), 'sort_date');
		$this->addColumn($this->pl->txt('table_column_tutor'));
		$this->addColumn($this->pl->txt('table_column_present'));
		$this->addColumn($this->pl->txt('table_column_excused'));
		$this->addColumn($this->pl->txt('table_column_unexcused'));
		$this->addColumn("", "", '30px', true);
	}

	protected function fillRowCSV($a_csv, $a_set) {
		unset($a_set['id']);
		unset($a_set['sort_date']);
		parent::fillRowCSV($a_csv, $a_set);
	}


	protected function fillRowExcel($a_worksheet, &$a_row, $a_set) {
		unset($a_set['id']);
		unset($a_set['sort_date']);
		parent::fillRowExcel($a_worksheet, $a_row, $a_set);
	}

}