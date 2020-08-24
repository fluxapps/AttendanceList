<?php

namespace srag\DataTableUI\AttendanceList\Component\Settings\Sort;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\AttendanceList\Component\Settings\Sort
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Factory
{

    /**
     * @param string $sort_field
     * @param int    $sort_field_direction
     *
     * @return SortField
     */
    public function sortField($sort_field, $sort_field_direction);
}
