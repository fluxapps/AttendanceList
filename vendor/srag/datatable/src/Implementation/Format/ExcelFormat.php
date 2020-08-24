<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Format;

use ilExcel;
use srag\DataTableUI\AttendanceList\Component\Column\Column;
use srag\DataTableUI\AttendanceList\Component\Data\Data;
use srag\DataTableUI\AttendanceList\Component\Data\Row\RowData;
use srag\DataTableUI\AttendanceList\Component\Settings\Settings;
use srag\DataTableUI\AttendanceList\Component\Table;

/**
 * Class ExcelFormat
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Format
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ExcelFormat extends AbstractFormat
{

    /**
     * @var ilExcel
     */
    protected $tpl;
    /**
     * @var int
     */
    protected $current_col = 0;
    /**
     * @var int
     */
    protected $current_row = 1;


    /**
     * @inheritDoc
     */
    public function getFormatId()
    {
        return self::FORMAT_EXCEL;
    }


    /**
     * @inheritDoc
     */
    protected function getFileExtension()
    {
        return "xlsx";
    }


    /**
     * @inheritDoc
     */
    protected function initTemplate(Table $component, ?Data $data, Settings $settings)
    {
        $this->tpl = new ilExcel();

        $this->tpl->addSheet($component->getTitle());
    }


    /**
     * @inheritDoc
     */
    public function getTemplate()
    {
        return (object) [
            "tpl"         => $this->tpl,
            "current_row" => $this->current_row,
            "current_col" => $this->current_col
        ];
    }


    /**
     * @inheritDoc
     */
    protected function handleColumns(Table $component, array $columns, Settings $settings)
    {
        $this->current_col = 0;

        parent::handleColumns($component, $columns, $settings);

        $this->current_row++;
    }


    /**
     * @inheritDoc
     */
    protected function handleColumn($formatted_column, Table $component, Column $column, Settings $settings)
    {
        $this->tpl->setCell($this->current_row, $this->current_col, $formatted_column);

        $this->current_col++;
    }


    /**
     * @inheritDoc
     */
    protected function handleRow(Table $component, array $columns, RowData $row)
    {
        $this->current_col = 0;

        parent::handleRow($component, $columns, $row);

        $this->current_row++;
    }


    /**
     * @inheritDoc
     */
    protected function handleRowColumn($formatted_row_column)
    {
        $this->tpl->setCell($this->current_row, $this->current_col, $formatted_row_column);

        $this->current_col++;
    }


    /**
     * @inheritDoc
     */
    protected function renderTemplate(Table $component)
    {
        $tmp_file = $this->tpl->writeToTmpFile();

        $data = file_get_contents($tmp_file);

        unlink($tmp_file);

        return $data;
    }
}
