<?php

namespace srag\DataTableUI\AttendanceList\Component\Data;

use srag\DataTableUI\AttendanceList\Component\Data\Row\RowData;

/**
 * Interface Data
 *
 * @package srag\DataTableUI\AttendanceList\Component\Data
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Data
{

    /**
     * @return RowData[]
     */
    public function getData();


    /**
     * @param RowData[] $data
     *
     * @return self
     */
    public function withData(array $data);


    /**
     * @return int
     */
    public function getMaxCount();


    /**
     * @param int $max_count
     *
     * @return self
     */
    public function withMaxCount($max_count);


    /**
     * @return int
     */
    public function getDataCount();
}
