<?php

namespace srag\DataTableUI\AttendanceList\Component\Data;

use srag\DataTableUI\AttendanceList\Component\Data\Row\RowData;

/**
 * Interface Data
 *
 * @package srag\DataTableUI\AttendanceList\Component\Data
 */
interface Data
{

    /**
     * @return RowData[]
     */
    public function getData() : array;


    /**
     * @return int
     */
    public function getDataCount() : int;


    /**
     * @return int
     */
    public function getMaxCount() : int;


    /**
     * @param RowData[] $data
     *
     * @return self
     */
    public function withData(array $data) : self;


    /**
     * @param int $max_count
     *
     * @return self
     */
    public function withMaxCount(int $max_count) : self;
}
