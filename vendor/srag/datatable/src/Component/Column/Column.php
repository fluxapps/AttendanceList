<?php

namespace srag\DataTableUI\AttendanceList\Component\Column;

use srag\DataTableUI\AttendanceList\Component\Column\Formatter\Formatter;
use srag\DataTableUI\AttendanceList\Component\Settings\Sort\SortField;

/**
 * Interface Column
 *
 * @package srag\DataTableUI\AttendanceList\Component\Column
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Column
{

    /**
     * @return string
     */
    public function getKey();


    /**
     * @param string $key
     *
     * @return self
     */
    public function withKey($key);


    /**
     * @return string
     */
    public function getTitle();


    /**
     * @param string $title
     *
     * @return self
     */
    public function withTitle($title);


    /**
     * @return Formatter
     */
    public function getFormatter();


    /**
     * @param Formatter $formatter
     *
     * @return self
     */
    public function withFormatter(Formatter $formatter);


    /**
     * @return bool
     */
    public function isSortable();


    /**
     * @param bool $sortable
     *
     * @return self
     */
    public function withSortable($sortable = true);


    /**
     * @return bool
     */
    public function isDefaultSort();


    /**
     * @param bool $default_sort
     *
     * @return self
     */
    public function withDefaultSort($default_sort = false);


    /**
     * @return int
     */
    public function getDefaultSortDirection();


    /**
     * @param int $default_sort_direction
     *
     * @return self
     */
    public function withDefaultSortDirection($default_sort_direction = SortField::SORT_DIRECTION_UP);


    /**
     * @return bool
     */
    public function isSelectable();


    /**
     * @param bool $selectable
     *
     * @return self
     */
    public function withSelectable($selectable = true);


    /**
     * @return bool
     */
    public function isDefaultSelected();


    /**
     * @param bool $default_selected
     *
     * @return self
     */
    public function withDefaultSelected($default_selected = true);


    /**
     * @return bool
     */
    public function isExportable();


    /**
     * @param bool $exportable
     *
     * @return self
     */
    public function withExportable($exportable = true);
}
