<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Column\Formatter;

use srag\CustomInputGUIs\AttendanceList\PropertyFormGUI\Items\Items;
use srag\DataTableUI\AttendanceList\Component\Column\Column;
use srag\DataTableUI\AttendanceList\Component\Data\Row\RowData;
use srag\DataTableUI\AttendanceList\Component\Format\Format;

/**
 * Class ChainGetterFormatter
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Column\Formatter
 */
class ChainGetterFormatter extends DefaultFormatter
{

    /**
     * @var array
     */
    protected $chain;


    /**
     * @inheritDoc
     *
     * @param array $chain
     */
    public function __construct(array $chain)
    {
        parent::__construct();

        $this->chain = $chain;
    }


    /**
     * @inheritDoc
     */
    public function formatRowCell(Format $format, $value, Column $column, RowData $row, string $table_id) : string
    {
        $chains = $this->chain;

        $value = $row(array_shift($chains));

        foreach ($chains as $chain) {
            if (is_array($value)) {
                $value = $value[$chain];
            } else {
                if (method_exists($value, $chain)) {
                    $value = $value->{$chain}();
                } else {
                    $value = Items::getter($value, $chain);
                }
            }
        }

        return parent::formatRowCell($format, $value, $column, $row, $table_id);
    }
}
