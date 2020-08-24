<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Column\Formatter;

use srag\DataTableUI\AttendanceList\Component\Column\Column;
use srag\DataTableUI\AttendanceList\Component\Data\Row\RowData;
use srag\DataTableUI\AttendanceList\Component\Format\Format;

/**
 * Class LanguageVariableFormatter
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Column\Formatter
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class LanguageVariableFormatter extends DefaultFormatter
{

    /**
     * @var string
     */
    protected $prefix;


    /**
     * @inheritDoc
     *
     * @param string $prefix
     */
    public function __construct($prefix)
    {
        parent::__construct();

        $this->prefix = $prefix;
    }


    /**
     * @inheritDoc
     */
    public function formatRowCell(Format $format, $value, Column $column, RowData $row, $table_id)
    {
        $value = strval($value);

        if (!empty($value)) {
            if (!empty($this->prefix)) {
                $value = rtrim($this->prefix, "_") . "_" . $value;
            }

            $value = self::dic()->language()->txt($value);
        }

        return parent::formatRowCell($format, $value, $column, $row, $table_id);
    }
}
