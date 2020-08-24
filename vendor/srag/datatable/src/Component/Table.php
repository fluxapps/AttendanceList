<?php

namespace srag\DataTableUI\AttendanceList\Component;

use ILIAS\UI\Component\Component;
use ILIAS\UI\Component\Input\Field\FilterInput;
use ILIAS\UI\Component\Input\Field\Input as FilterInput54;
use srag\DataTableUI\AttendanceList\Component\Column\Column;
use srag\DataTableUI\AttendanceList\Component\Data\Fetcher\DataFetcher;
use srag\DataTableUI\AttendanceList\Component\Format\Browser\BrowserFormat;
use srag\DataTableUI\AttendanceList\Component\Format\Format;
use srag\DataTableUI\AttendanceList\Component\Settings\Storage\SettingsStorage;
use srag\DIC\AttendanceList\Plugin\Pluginable;
use srag\DIC\AttendanceList\Plugin\PluginInterface;

/**
 * Interface Table
 *
 * @package srag\DataTableUI\AttendanceList\Component
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Table extends Component, Pluginable
{

    /**
     * @var string
     */
    const ACTION_GET_VAR = "row_id";
    /**
     * @var string
     */
    const MULTIPLE_SELECT_POST_VAR = "selected_row_ids";
    /**
     * @var string
     */
    const LANG_MODULE = "datatableui";


    /**
     * @param PluginInterface $plugin
     *
     * @return self
     */
    public function withPlugin(PluginInterface $plugin);


    /**
     * @return string
     */
    public function getTableId();


    /**
     * @param string $table_id
     *
     * @return self
     */
    public function withTableId($table_id);


    /**
     * @return string
     */
    public function getActionUrl();


    /**
     * @param string $action_url
     *
     * @return self
     */
    public function withActionUrl($action_url);


    /**
     * @return string
     */
    public function getTitle();


    /**
     * @param string $title
     *
     * @return self
     */
    public function withTitle($title);


    /**
     * @return Column[]
     */
    public function getColumns();


    /**
     * @param Column[] $columns
     *
     * @return self
     */
    public function withColumns(array $columns);


    /**
     * @return DataFetcher
     */
    public function getDataFetcher();


    /**
     * @param DataFetcher $data_fetcher
     *
     * @return self
     */
    public function withFetchData(DataFetcher $data_fetcher);


    /**
     * @return FilterInput[]|FilterInput54[]
     */
    public function getFilterFields();


    /**
     * @param FilterInput[]|FilterInput54[] $filter_fields
     *
     * @return self
     */
    public function withFilterFields(array $filter_fields);


    /**
     * @return BrowserFormat
     */
    public function getBrowserFormat();


    /**
     * @param BrowserFormat $browser_format
     *
     * @return self
     */
    public function withBrowserFormat(BrowserFormat $browser_format);


    /**
     * @return Format[]
     */
    public function getFormats();


    /**
     * @param Format[] $formats
     *
     * @return self
     */
    public function withFormats(array $formats);


    /**
     * @return string[]
     */
    public function getMultipleActions();


    /**
     * @param string[] $multiple_actions
     *
     * @return self
     */
    public function withMultipleActions(array $multiple_actions);


    /**
     * @return SettingsStorage
     */
    public function getSettingsStorage();


    /**
     * @param SettingsStorage $settings_storage
     *
     * @return self
     */
    public function withSettingsStorage(SettingsStorage $settings_storage);
}
