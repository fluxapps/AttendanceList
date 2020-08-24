<?php

namespace srag\DataTableUI\AttendanceList\Component\Data;

use srag\DataTableUI\AttendanceList\Component\Data\Fetcher\Factory as FetcherFactory;
use srag\DataTableUI\AttendanceList\Component\Data\Row\Factory as RowFactory;
use srag\DataTableUI\AttendanceList\Component\Data\Row\RowData;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\AttendanceList\Component\Data
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Factory
{

    /**
     * @param RowData[] $data
     * @param int       $max_count
     *
     * @return Data
     */
    public function data(array $data, int $max_count) : Data;


    /**
     * @return FetcherFactory
     */
    public function fetcher() : FetcherFactory;


    /**
     * @return RowFactory
     */
    public function row() : RowFactory;
}
