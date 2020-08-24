<?php

namespace srag\DataTableUI\AttendanceList\Implementation;

use ILIAS\UI\Component\Input\Field\FilterInput;
use ILIAS\UI\Component\Input\Field\Input as FilterInput54;
use ILIAS\UI\Implementation\Component\ComponentHelper;
use srag\DataTableUI\AttendanceList\Component\Column\Column;
use srag\DataTableUI\AttendanceList\Component\Data\Fetcher\DataFetcher;
use srag\DataTableUI\AttendanceList\Component\Format\Browser\BrowserFormat;
use srag\DataTableUI\AttendanceList\Component\Format\Format;
use srag\DataTableUI\AttendanceList\Component\Settings\Storage\SettingsStorage;
use srag\DataTableUI\AttendanceList\Component\Table as TableInterface;
use srag\DataTableUI\AttendanceList\Implementation\Utils\DataTableUITrait;
use srag\DIC\AttendanceList\DICTrait;
use srag\DIC\AttendanceList\Plugin\PluginInterface;

/**
 * Class Table
 *
 * @package srag\DataTableUI\AttendanceList\Implementation
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Table implements TableInterface
{

    use ComponentHelper;
    use DICTrait;
    use DataTableUITrait;

    /**
     * @var PluginInterface
     */
    protected $plugin;
    /**
     * @var string
     */
    protected $table_id = "";
    /**
     * @var string
     */
    protected $action_url = "";
    /**
     * @var string
     */
    protected $title = "";
    /**
     * @var Column[]
     */
    protected $columns = [];
    /**
     * @var DataFetcher
     */
    protected $data_fetcher;
    /**
     * @var FilterInput[]|FilterInput54[]
     */
    protected $filter_fields = [];
    /**
     * @var BrowserFormat
     */
    protected $browser_format;
    /**
     * @var Format[]
     */
    protected $formats = [];
    /**
     * @var string[]
     */
    protected $multiple_actions = [];
    /**
     * @var SettingsStorage
     */
    protected $settings_storage;


    /**
     * Table constructor
     *
     * @param string      $table_id
     * @param string      $action_url
     * @param string      $title
     * @param Column[]    $columns
     * @param DataFetcher $data_fetcher
     */
    public function __construct($table_id, $action_url, $title, array $columns, DataFetcher $data_fetcher)
    {
        $this->table_id = $table_id;

        $this->action_url = $action_url;

        $this->title = $title;

        $classes = [Column::class];
        $this->checkArgListElements("columns", $columns, $classes);
        $this->columns = $columns;

        $this->data_fetcher = $data_fetcher;
    }


    /**
     * @inheritDoc
     */
    public function getPlugin()
    {
        return $this->plugin;
    }


    /**
     * @inheritDoc
     */
    public function withPlugin(PluginInterface $plugin)
    {
        $clone = clone $this;

        $clone->plugin = $plugin;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function getTableId()
    {
        return $this->table_id;
    }


    /**
     * @inheritDoc
     */
    public function withTableId($table_id)
    {
        $clone = clone $this;

        $clone->table_id = $table_id;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function getActionUrl()
    {
        return $this->action_url;
    }


    /**
     * @inheritDoc
     */
    public function withActionUrl($action_url)
    {
        $clone = clone $this;

        $clone->action_url = $action_url;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * @inheritDoc
     */
    public function withTitle($title)
    {
        $clone = clone $this;

        $clone->title = $title;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function getColumns()
    {
        return $this->columns;
    }


    /**
     * @inheritDoc
     */
    public function withColumns(array $columns)
    {
        $classes = [Column::class];
        $this->checkArgListElements("columns", $columns, $classes);

        $clone = clone $this;

        $clone->columns = $columns;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function getDataFetcher()
    {
        return $this->data_fetcher;
    }


    /**
     * @inheritDoc
     */
    public function withFetchData(DataFetcher $data_fetcher)
    {
        $clone = clone $this;

        $clone->data_fetcher = $data_fetcher;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function getFilterFields()
    {
        return $this->filter_fields;
    }


    /**
     * @inheritDoc
     */
    public function withFilterFields(array $filter_fields)
    {
        if (self::version()->is6()) {
            $classes = [FilterInput::class];
        } else {

            $classes = [FilterInput54::class];
        }
        $this->checkArgListElements("filter_fields", $filter_fields, $classes);

        $clone = clone $this;

        $clone->filter_fields = $filter_fields;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function getBrowserFormat()
    {
        if ($this->browser_format === null) {
            $this->browser_format = self::dataTableUI()->format()->browser()->default();
        }

        return $this->browser_format;
    }


    /**
     * @inheritDoc
     */
    public function withBrowserFormat(BrowserFormat $browser_format)
    {
        $clone = clone $this;

        $clone->browser_format = $browser_format;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function getFormats()
    {
        return $this->formats;
    }


    /**
     * @inheritDoc
     */
    public function withFormats(array $formats)
    {
        $classes = [Format::class];
        $this->checkArgListElements("formats", $formats, $classes);

        $clone = clone $this;

        $clone->formats = $formats;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function getMultipleActions()
    {
        return $this->multiple_actions;
    }


    /**
     * @inheritDoc
     */
    public function withMultipleActions(array $multiple_actions)
    {
        $clone = clone $this;

        $clone->multiple_actions = $multiple_actions;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function getSettingsStorage()
    {
        if ($this->settings_storage === null) {
            $this->settings_storage = self::dataTableUI()->settings()->storage()->default();
        }

        return $this->settings_storage;
    }


    /**
     * @inheritDoc
     */
    public function withSettingsStorage(SettingsStorage $settings_storage)
    {
        $clone = clone $this;

        $clone->settings_storage = $settings_storage;

        return $clone;
    }
}
