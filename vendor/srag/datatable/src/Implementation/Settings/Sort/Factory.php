<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Settings\Sort;

use srag\DataTableUI\AttendanceList\Component\Settings\Sort\Factory as FactoryInterface;
use srag\DataTableUI\AttendanceList\Component\Settings\Sort\SortField as SortFieldInterface;
use srag\DataTableUI\AttendanceList\Implementation\Utils\DataTableUITrait;
use srag\DIC\AttendanceList\DICTrait;

/**
 * Class Factory
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Settings\Sort
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Factory implements FactoryInterface
{

    use DICTrait;
    use DataTableUITrait;

    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * @return self
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * Factory constructor
     */
    private function __construct()
    {

    }


    /**
     * @inheritDoc
     */
    public function sortField($sort_field, $sort_field_direction)
    {
        return new SortField($sort_field, $sort_field_direction);
    }
}
