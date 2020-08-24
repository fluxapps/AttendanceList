<?php

namespace srag\DataTableUI\AttendanceList\Component\Format\Browser;

use srag\DataTableUI\AttendanceList\Component\Format\Browser\Filter\Factory as FilterFactory;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\AttendanceList\Component\Format\Browser
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Factory
{

    /**
     * @return BrowserFormat
     */
    public function default();


    /**
     * @return FilterFactory
     */
    public function filter();
}
