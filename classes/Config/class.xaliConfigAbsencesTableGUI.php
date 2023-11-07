<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
/**
 * Class xaliConfigAbsencesTableGUI
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xaliConfigAbsencesTableGUI extends ilTable2GUI {
	protected ilAttendanceListPlugin $pl;
	protected int $obj_id;
	protected ilCtrl $ctrl;


	/**
	 * xaliConfigAbsencesTableGUI constructor.
	 *
	 * @param ilAttendanceListConfigGUI $a_parent_obj
	 */
	public function __construct(ilAttendanceListConfigGUI $a_parent_obj) {
		global $DIC;
		$lng = $DIC['lng'];
		$ilCtrl = $DIC['ilCtrl'];
		$this->lng = $lng;
		$this->ctrl = $ilCtrl;
		$this->pl = ilAttendanceListPlugin::getInstance();
		$this->setId('xali_config_absences');

		parent::__construct($a_parent_obj, ilAttendanceListConfigGUI::CMD_STANDARD);
		$this->setRowTemplate('tpl.config_absences_row.html', $this->pl->getDirectory());

		$this->initColumns();

		$this->setFormAction($this->ctrl->getFormAction($a_parent_obj));
		$this->setTitle($this->pl->txt('absence_reasons'));

//		$this->setDefaultOrderField('sort_date');

		$this->parseData();
	}

	protected function initColumns(): void
    {
		$this->addColumn($this->pl->txt('table_column_' . xaliAbsenceReason::F_ABSENCE_REASONS_TITLE));
		$this->addColumn($this->pl->txt('table_column_' . xaliAbsenceReason::F_ABSENCE_REASONS_INFO));
		$this->addColumn($this->pl->txt('table_column_' . xaliAbsenceReason::F_ABSENCE_REASONS_HAS_COMMENT));
		$this->addColumn($this->pl->txt('table_column_' . xaliAbsenceReason::F_ABSENCE_REASONS_COMMENT_REQ));
		$this->addColumn($this->pl->txt('table_column_' . xaliAbsenceReason::F_ABSENCE_REASONS_HAS_UPLOAD));
		$this->addColumn($this->pl->txt('table_column_' . xaliAbsenceReason::F_ABSENCE_REASONS_UPLOAD_REQ));
		$this->addColumn("", "", '30px', true);
	}

	protected function parseData(): void
    {
		$this->setData(xaliAbsenceReason::getArray());
	}

	protected function fillRow(array $a_set): void
    {
		$a_set['action'] = $this->buildAction($a_set);
		parent::fillRow($a_set);
	}

    /**
     * @throws ilCtrlException
     * @throws JsonException
     */
    protected function buildAction($a_set): string
    {
		$actions = new ilAdvancedSelectionListGUI();
		$actions->setListTitle($this->lng->txt('actions'));

		$this->ctrl->setParameter($this->parent_obj, 'ar_id', $a_set['id']);
		$actions->addItem($this->lng->txt('edit'), '',$this->ctrl->getLinkTarget($this->parent_obj, ilAttendanceListConfigGUI::CMD_EDIT_REASON));
		$actions->addItem($this->lng->txt('delete'), '', $this->ctrl->getLinkTarget($this->parent_obj, ilAttendanceListConfigGUI::CMD_DELETE_REASON));

		return $actions->getHTML();
	}
}