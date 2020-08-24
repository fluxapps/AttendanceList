<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Data;

use srag\DataTableUI\AttendanceList\Component\Data\Data as DataInterface;
use srag\DataTableUI\AttendanceList\Component\Data\Factory as FactoryInterface;
use srag\DataTableUI\AttendanceList\Component\Data\Fetcher\Factory as FetcherFactoryInterface;
use srag\DataTableUI\AttendanceList\Component\Data\Row\Factory as RowFactoryInterface;
use srag\DataTableUI\AttendanceList\Implementation\Data\Fetcher\Factory as FetcherFactory;
use srag\DataTableUI\AttendanceList\Implementation\Data\Row\Factory as RowFactory;
use srag\DataTableUI\AttendanceList\Implementation\Utils\DataTableUITrait;
use srag\DIC\AttendanceList\DICTrait;

/**
 * Class Factory
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Data
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
    public function data(array $data, $max_count)
    {
        return new Data($data, $max_count);
    }


    /**
     * @inheritDoc
     */
    public function fetcher()
    {
        return FetcherFactory::getInstance();
    }


    /**
     * @inheritDoc
     */
    public function row()
    {
        return RowFactory::getInstance();
    }
}
