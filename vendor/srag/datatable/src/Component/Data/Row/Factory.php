<?php

namespace srag\DataTableUI\AttendanceList\Component\Data\Row;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\AttendanceList\Component\Data\Row
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Factory
{

    /**
     * @param string $row_id
     * @param object $original_data
     *
     * @return RowData
     */
    public function getter($row_id, object $original_data);


    /**
     * @param string $row_id
     * @param object $original_data
     *
     * @return RowData
     */
    public function property($row_id, object $original_data);
}
