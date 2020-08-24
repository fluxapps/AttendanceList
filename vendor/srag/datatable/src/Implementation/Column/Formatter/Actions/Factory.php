<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Column\Formatter\Actions;

use srag\DataTableUI\AttendanceList\Component\Column\Formatter\Actions\ActionsFormatter;
use srag\DataTableUI\AttendanceList\Component\Column\Formatter\Actions\Factory as FactoryInterface;
use srag\DataTableUI\AttendanceList\Implementation\Utils\DataTableUITrait;
use srag\DIC\AttendanceList\DICTrait;

/**
 * Class Factory
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Column\Formatter\Actions
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
    public static function getInstance() : self
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
    public function actionsDropdown() : ActionsFormatter
    {
        return new ActionsDropdownFormatter();
    }


    /**
     * @inheritDoc
     */
    public function sort() : ActionsFormatter
    {
        return new SortFormatter();
    }
}
