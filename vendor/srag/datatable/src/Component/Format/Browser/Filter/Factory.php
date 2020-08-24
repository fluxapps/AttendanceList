<?php

namespace srag\DataTableUI\AttendanceList\Component\Format\Browser\Filter;

use srag\CustomInputGUIs\AttendanceList\FormBuilder\FormBuilder;
use srag\DataTableUI\AttendanceList\Component\Format\Browser\BrowserFormat;
use srag\DataTableUI\AttendanceList\Component\Settings\Settings;
use srag\DataTableUI\AttendanceList\Component\Table;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\AttendanceList\Component\Format\Browser\Filter
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Factory
{

    /**
     * @param BrowserFormat $parent
     * @param Table         $component
     * @param Settings      $settings
     *
     * @return FormBuilder
     */
    public function formBuilder(BrowserFormat $parent, Table $component, Settings $settings);
}
