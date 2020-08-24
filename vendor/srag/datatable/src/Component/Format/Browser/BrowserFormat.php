<?php

namespace srag\DataTableUI\AttendanceList\Component\Format\Browser;

use srag\DataTableUI\AttendanceList\Component\Format\Format;
use srag\DataTableUI\AttendanceList\Component\Settings\Settings;
use srag\DataTableUI\AttendanceList\Component\Table;

/**
 * Interface BrowserFormat
 *
 * @package srag\DataTableUI\AttendanceList\Component\Format\Browser
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface BrowserFormat extends Format
{

    /**
     * @param Table $component
     *
     * @return string|null
     */
    public function getInputFormatId(Table $component);


    /**
     * @param Table    $component
     * @param Settings $settings
     *
     * @return Settings
     */
    public function handleSettingsInput(Table $component, Settings $settings);


    /**
     * @param string $action_url
     * @param array  $params
     * @param string $table_id
     *
     * @return string
     */
    public function getActionUrlWithParams($action_url, array $params, $table_id);


    /**
     * @param string $key
     * @param string $table_id
     *
     * @return string
     */
    public function actionParameter($key, $table_id);


    /**
     * @param string $table_id
     *
     * @return string
     */
    public function getActionRowId($table_id);


    /**
     * @param string $table_id
     *
     * @return string[]
     */
    public function getMultipleActionRowIds($table_id);
}
