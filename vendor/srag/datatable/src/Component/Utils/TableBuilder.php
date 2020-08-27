<?php

namespace srag\DataTableUI\AttendanceList\Component\Utils;

use srag\DataTableUI\AttendanceList\Component\Table;

/**
 * Interface TableBuilder
 *
 * @package srag\DataTableUI\AttendanceList\Component\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface TableBuilder
{

    /**
     * @return Table
     */
    public function getTable() : Table;


    /**
     * @return string
     */
    public function render() : string;
}
