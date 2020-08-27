<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Column;

use srag\DataTableUI\AttendanceList\Component\Column\Column as ColumnInterface;
use srag\DataTableUI\AttendanceList\Component\Column\Factory as FactoryInterface;
use srag\DataTableUI\AttendanceList\Component\Column\Formatter\Factory as FormatterFactoryInterface;
use srag\DataTableUI\AttendanceList\Implementation\Column\Formatter\Factory as FormatterFactory;
use srag\DataTableUI\AttendanceList\Implementation\Utils\DataTableUITrait;
use srag\DIC\AttendanceList\DICTrait;

/**
 * Class Factory
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Column
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
    public function column(string $key, string $title) : ColumnInterface
    {
        return new Column($key, $title);
    }


    /**
     * @inheritDoc
     */
    public function formatter() : FormatterFactoryInterface
    {
        return FormatterFactory::getInstance();
    }
}
