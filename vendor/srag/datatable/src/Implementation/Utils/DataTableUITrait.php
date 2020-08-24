<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Utils;

use srag\DataTableUI\AttendanceList\Component\Factory as FactoryInterface;
use srag\DataTableUI\AttendanceList\Implementation\Factory;

/**
 * Trait DataTableUITrait
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
trait DataTableUITrait
{

    /**
     * @return FactoryInterface
     */
    protected static function dataTableUI() : FactoryInterface
    {
        return Factory::getInstance();
    }
}
