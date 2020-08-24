<?php

namespace srag\DataTableUI\AttendanceList\Component\Settings;

use ILIAS\UI\Component\ViewControl\Pagination;
use srag\DataTableUI\AttendanceList\Component\Data\Data;
use srag\DataTableUI\AttendanceList\Component\Settings\Sort\SortField;

/**
 * Interface Settings
 *
 * @package srag\DataTableUI\AttendanceList\Component\Settings
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Settings
{

    /**
     * @var int
     */
    const DEFAULT_ROWS_COUNT = 50;
    /**
     * @var int[]
     */
    const ROWS_COUNT
        = [
            5,
            10,
            15,
            20,
            30,
            40,
            self::DEFAULT_ROWS_COUNT,
            100,
            200,
            400,
            800
        ];


    /**
     * @param mixed[] $key
     *
     * @return array
     */
    public function getFilterFieldValues();


    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getFilterFieldValue($key);


    /**
     * @param mixed[] $filter_field_values
     *
     * @return self
     */
    public function withFilterFieldValues(array $filter_field_values);


    /**
     * @return SortField[]
     */
    public function getSortFields();


    /**
     * @param string $sort_field
     *
     * @return SortField|null
     */
    public function getSortField($sort_field);


    /**
     * @param SortField[] $sort_fields
     *
     * @return self
     */
    public function withSortFields(array $sort_fields);


    /**
     * @param SortField $sort_field
     *
     * @return self
     */
    public function addSortField(SortField $sort_field);


    /**
     * @param string $sort_field
     *
     * @return self
     */
    public function removeSortField($sort_field);


    /**
     * @return string[]
     */
    public function getSelectedColumns();


    /**
     * @param string[] $selected_columns
     *
     * @return self
     */
    public function withSelectedColumns(array $selected_columns);


    /**
     * @param string $selected_column
     *
     * @return self
     */
    public function selectColumn($selected_column);


    /**
     * @param string $selected_column
     *
     * @return self
     */
    public function deselectColumn($selected_column);


    /**
     * @return bool
     */
    public function isFilterSet();


    /**
     * @param bool $filter_set
     *
     * @return self
     */
    public function withFilterSet($filter_set = false);


    /**
     * @return int
     */
    public function getRowsCount();


    /**
     * @param int $rows_count
     *
     * @return self
     */
    public function withRowsCount($rows_count = self::DEFAULT_ROWS_COUNT);


    /**
     * @return int
     */
    public function getCurrentPage();


    /**
     * @param int $current_page
     *
     * @return self
     */
    public function withCurrentPage($current_page = 0);


    /**
     * @return int
     */
    public function getOffset();


    /**
     * @param Data|null $data
     *
     * @return Pagination
     *
     * @internal
     */
    public function getPagination(?Data $data);
}
