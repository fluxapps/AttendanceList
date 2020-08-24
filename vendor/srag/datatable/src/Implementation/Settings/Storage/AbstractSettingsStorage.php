<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Settings\Storage;

use srag\DataTableUI\AttendanceList\Component\Column\Column;
use srag\DataTableUI\AttendanceList\Component\Settings\Settings;
use srag\DataTableUI\AttendanceList\Component\Settings\Sort\SortField;
use srag\DataTableUI\AttendanceList\Component\Settings\Storage\SettingsStorage;
use srag\DataTableUI\AttendanceList\Component\Table;
use srag\DataTableUI\AttendanceList\Implementation\Utils\DataTableUITrait;
use srag\DIC\AttendanceList\DICTrait;

/**
 * Class AbstractSettingsStorage
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Settings\Storage
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractSettingsStorage implements SettingsStorage
{

    use DICTrait;
    use DataTableUITrait;

    /**
     * AbstractSettingsStorage constructor
     */
    public function __construct()
    {

    }


    /**
     * @inheritDoc
     */
    public function handleDefaultSettings(Settings $settings, Table $component)
    {
        if (!$settings->isFilterSet() && empty($settings->getSortFields())) {
            $settings = $settings->withSortFields(array_map(function (Column $column) use($component) {    return self::dataTableUI()->settings()->sort()->sortField($column->getKey(), $column->getDefaultSortDirection());
}, array_filter($component->getColumns(), function (Column $column) {
    return $column->isSortable() && $column->isDefaultSort();
})));
        }

        if (!$settings->isFilterSet() && empty($settings->getSelectedColumns())) {
            $settings = $settings->withSelectedColumns(array_map(function (Column $column) {
    return $column->getKey();
}, array_filter($component->getColumns(), function (Column $column) {
    return $column->isSelectable() && $column->isDefaultSelected();
})));
        }

        return $settings;
    }
}
