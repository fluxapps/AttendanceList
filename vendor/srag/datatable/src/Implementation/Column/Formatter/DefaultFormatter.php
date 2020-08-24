<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Column\Formatter;

use ilExcel;
use srag\DataTableUI\AttendanceList\Component\Column\Column;
use srag\DataTableUI\AttendanceList\Component\Data\Row\RowData;
use srag\DataTableUI\AttendanceList\Component\Format\Format;

/**
 * Class DefaultFormatter
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Column\Formatter
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class DefaultFormatter extends AbstractFormatter
{

    /**
     * @inheritDoc
     */
    public function formatHeaderCell(Format $format, Column $column, $table_id)
    {
        $title = $column->getTitle();

        switch ($format->getFormatId()) {
            case Format::FORMAT_CSV:
                return $title;

            case Format::FORMAT_EXCEL:
                /**
                 * @var ilExcel $tpl
                 */ $tpl = $format->getTemplate()->tpl;
                $cord = $tpl->getColumnCoord($format->getTemplate()->current_col) . $format->getTemplate()->current_row;
                $tpl->setBold($cord . ":" . $cord);

                return $title;

            case Format::FORMAT_PDF:
                return "<b>" . htmlspecialchars($title) . "</b>";

            default:
                return htmlspecialchars($title);
        }
    }


    /**
     * @inheritDoc
     */
    public function formatRowCell(Format $format, $value, Column $column, RowData $row, $table_id)
    {
        $value = strval($value);

        switch ($format->getFormatId()) {
            case Format::FORMAT_BROWSER:
            case Format::FORMAT_HTML:
            case Format::FORMAT_PDF:
                if (empty($value)) {
                    return "&nbsp;";
                }

                return htmlspecialchars($value);

            default:
                return $value;
        }
    }
}
