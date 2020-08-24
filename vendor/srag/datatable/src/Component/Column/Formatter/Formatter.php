<?php

namespace srag\DataTableUI\AttendanceList\Component\Column\Formatter;

use srag\DataTableUI\AttendanceList\Component\Column\Column;
use srag\DataTableUI\AttendanceList\Component\Data\Row\RowData;
use srag\DataTableUI\AttendanceList\Component\Format\Format;

/**
 * Interface Formatter
 *
 * @package srag\DataTableUI\AttendanceList\Component\Column\Formatter
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Formatter
{

    /**
     * @param Format $format
     * @param Column $column
     * @param string $table_id
     *
     * @return string
     */
    public function formatHeaderCell(Format $format, Column $column, $table_id);


    /**
     * @param Format  $format
     * @param mixed   $value
     * @param Column  $column
     * @param RowData $row
     * @param string  $table_id
     *
     * @return string
     */
    public function formatRowCell(Format $format, $value, Column $column, RowData $row, $table_id);
}
