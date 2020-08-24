<?php

namespace srag\DataTableUI\AttendanceList\Component\Format;

use srag\DataTableUI\AttendanceList\Component\Format\Browser\Factory as BrowserFactory;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\AttendanceList\Component\Format
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Factory
{

    /**
     * @return BrowserFactory
     */
    public function browser();


    /**
     * @return Format
     */
    public function csv();


    /**
     * @return Format
     */
    public function excel();


    /**
     * @return Format
     */
    public function html();


    /**
     * @return Format
     */
    public function pdf();
}
