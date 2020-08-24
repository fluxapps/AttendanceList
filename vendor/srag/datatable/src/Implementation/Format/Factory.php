<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Format;

use srag\DataTableUI\AttendanceList\Component\Format\Browser\Factory as BrowserFactoryInterface;
use srag\DataTableUI\AttendanceList\Component\Format\Factory as FactoryInterface;
use srag\DataTableUI\AttendanceList\Component\Format\Format;
use srag\DataTableUI\AttendanceList\Implementation\Format\Browser\Factory as BrowserFactory;
use srag\DataTableUI\AttendanceList\Implementation\Utils\DataTableUITrait;
use srag\DIC\AttendanceList\DICTrait;

/**
 * Class Factory
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Format
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
    public function browser()
    {
        return BrowserFactory::getInstance();
    }


    /**
     * @inheritDoc
     */
    public function csv()
    {
        return new CsvFormat();
    }


    /**
     * @inheritDoc
     */
    public function excel()
    {
        return new ExcelFormat();
    }


    /**
     * @inheritDoc
     */
    public function html()
    {
        return new HtmlFormat();
    }


    /**
     * @inheritDoc
     */
    public function pdf()
    {
        return new PdfFormat();
    }
}
