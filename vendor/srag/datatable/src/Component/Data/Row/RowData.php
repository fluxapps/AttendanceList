<?php

namespace srag\DataTableUI\AttendanceList\Component\Data\Row;

/**
 * Interface RowData
 *
 * @package srag\DataTableUI\AttendanceList\Component\Data\Row
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface RowData
{

    /**
     * @return string
     */
    public function getRowId();


    /**
     * @param string $row_id
     *
     * @return self
     */
    public function withRowId($row_id);


    /**
     * @return object
     */
    public function getOriginalData();


    /**
     * @param object $original_data
     *
     * @return self
     */
    public function withOriginalData(object $original_data);


    /**
     * @param string $key
     *
     * @return mixed
     */
    public function __invoke($key);
}
