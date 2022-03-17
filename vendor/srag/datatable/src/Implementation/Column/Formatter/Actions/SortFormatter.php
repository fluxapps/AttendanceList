<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Column\Formatter\Actions;

use srag\CustomInputGUIs\AttendanceList\Waiter\Waiter;
use srag\DataTableUI\AttendanceList\Component\Column\Column;
use srag\DataTableUI\AttendanceList\Component\Column\Formatter\Actions\ActionsFormatter;
use srag\DataTableUI\AttendanceList\Component\Data\Row\RowData;
use srag\DataTableUI\AttendanceList\Component\Format\Format;
use srag\DataTableUI\AttendanceList\Component\Table;
use srag\DataTableUI\AttendanceList\Implementation\Column\Formatter\DefaultFormatter;

/**
 * Class SortFormatter
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Column\Formatter\Actions
 */
class SortFormatter extends DefaultFormatter implements ActionsFormatter
{

    /**
     * @inheritDoc
     */
    public function formatRowCell(Format $format, $actions, Column $column, RowData $row, string $table_id) : string
    {
        return self::output()->getHTML([
            self::dic()->ui()->factory()->symbol()->glyph()->sortAscending()->withAdditionalOnLoadCode(function (string $id) use ($format, $row, $column, $table_id) : string {
                Waiter::init(Waiter::TYPE_WAITER/*, null, $component->getPlugin()*/); // TODO: Pass $component

                return '
            $("#' . $id . '").click(function () {
                il.waiter.show();
                var row = $(this).parent().parent();
                $.ajax({
                    url: ' . json_encode($format->getActionUrlWithParams($row($column->getKey() . "_up_action_url"), [Table::ACTION_GET_VAR => $row->getRowId()], $table_id)) . ',
                    type: "GET"
                 }).always(function () {
                    il.waiter.hide();
               }).success(function() {
                    row.insertBefore(row.prev());
                });
            });';
            }),
            self::dic()->ui()->factory()->symbol()->glyph()->sortDescending()->withAdditionalOnLoadCode(function (string $id) use ($format, $row, $column, $table_id) : string {
                return '
            $("#' . $id . '").click(function () {
                il.waiter.show();
                var row = $(this).parent().parent();
                $.ajax({
                     url: ' . json_encode($format->getActionUrlWithParams($row($column->getKey() . "_down_action_url"), [Table::ACTION_GET_VAR => $row->getRowId()], $table_id)) . ',
                    type: "GET"
                }).always(function () {
                    il.waiter.hide();
                }).success(function() {
                    row.insertAfter(row.next());
                });
        });';
            })
        ]);
    }
}
