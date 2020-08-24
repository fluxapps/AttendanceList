<?php

namespace srag\DataTableUI\AttendanceList\Component;

use srag\DataTableUI\AttendanceList\Component\Column\Column;
use srag\DataTableUI\AttendanceList\Component\Column\Factory as ColumnFactory;
use srag\DataTableUI\AttendanceList\Component\Data\Factory as DataFactory;
use srag\DataTableUI\AttendanceList\Component\Data\Fetcher\DataFetcher;
use srag\DataTableUI\AttendanceList\Component\Format\Factory as FormatFactory;
use srag\DataTableUI\AttendanceList\Component\Settings\Factory as SettingsFactory;
use srag\DIC\AttendanceList\Plugin\PluginInterface;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\AttendanceList\Component
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Factory
{

    /**
     * @return ColumnFactory
     */
    public function column();


    /**
     * @return DataFactory
     */
    public function data();


    /**
     * @return FormatFactory
     */
    public function format();


    /**
     * @return SettingsFactory
     */
    public function settings();


    /**
     * @param string      $table_id
     * @param string      $action_url
     * @param string      $title
     * @param Column[]    $columns
     * @param DataFetcher $data_fetcher
     *
     * @return Table
     */
    public function table($table_id, $action_url, $title, array $columns, DataFetcher $data_fetcher);


    /**
     * @param PluginInterface $plugin
     */
    public function installLanguages(PluginInterface $plugin);
}
