<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Format;

use ilCSVWriter;
use srag\DataTableUI\AttendanceList\Component\Column\Column;
use srag\DataTableUI\AttendanceList\Component\Data\Data;
use srag\DataTableUI\AttendanceList\Component\Data\Row\RowData;
use srag\DataTableUI\AttendanceList\Component\Settings\Settings;
use srag\DataTableUI\AttendanceList\Component\Table;

/**
 * Class CsvFormat
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Format
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CsvFormat extends AbstractFormat
{

    /**
     * @var ilCSVWriter
     */
    protected $tpl;


    /**
     * @inheritDoc
     */
    public function getFormatId()
    {
        return self::FORMAT_CSV;
    }


    /**
     * @inheritDoc
     */
    protected function getFileExtension()
    {
        return "csv";
    }


    /**
     * @inheritDoc
     */
    protected function initTemplate(Table $component, ?Data $data, Settings $settings)
    {
        $this->tpl = new ilCSVWriter();

        $this->tpl->setSeparator(";");
    }


    /**
     * @inheritDoc
     */
    protected function handleColumns(Table $component, array $columns, Settings $settings)
    {
        parent::handleColumns($component, $columns, $settings);

        $this->tpl->addRow();
    }


    /**
     * @inheritDoc
     */
    protected function handleColumn($formatted_column, Table $component, Column $column, Settings $settings)
    {
        $this->tpl->addColumn($formatted_column);
    }


    /**
     * @inheritDoc
     */
    protected function handleRow(Table $component, array $columns, RowData $row)
    {
        parent::handleRow($component, $columns, $row);

        $this->tpl->addRow();
    }


    /**
     * @inheritDoc
     */
    protected function handleRowColumn($formatted_row_column)
    {
        $this->tpl->addColumn($formatted_row_column);
    }


    /**
     * @inheritDoc
     */
    protected function renderTemplate(Table $component)
    {
        return $this->tpl->getCSVString();
    }
}
