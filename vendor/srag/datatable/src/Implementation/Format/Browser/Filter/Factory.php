<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Format\Browser\Filter;

use srag\CustomInputGUIs\AttendanceList\FormBuilder\FormBuilder as FormBuilderInterface;
use srag\DataTableUI\AttendanceList\Component\Format\Browser\BrowserFormat;
use srag\DataTableUI\AttendanceList\Component\Format\Browser\Filter\Factory as FactoryInterface;
use srag\DataTableUI\AttendanceList\Component\Settings\Settings;
use srag\DataTableUI\AttendanceList\Component\Table;
use srag\DataTableUI\AttendanceList\Implementation\Utils\DataTableUITrait;
use srag\DIC\AttendanceList\DICTrait;

/**
 * Class Factory
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Format\Browser\Filter
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
    public function formBuilder(BrowserFormat $parent, Table $component, Settings $settings) : FormBuilderInterface
    {
        return new FormBuilder($parent, $component, $settings);
    }
}
