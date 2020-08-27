<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Format\Browser\Filter;

use srag\CustomInputGUIs\AttendanceList\FormBuilder\AbstractFormBuilder;
use srag\DataTableUI\AttendanceList\Component\Format\Browser\BrowserFormat;
use srag\DataTableUI\AttendanceList\Component\Settings\Settings;
use srag\DataTableUI\AttendanceList\Component\Settings\Storage\SettingsStorage;
use srag\DataTableUI\AttendanceList\Component\Table;
use srag\DataTableUI\AttendanceList\Implementation\Utils\DataTableUITrait;

/**
 * Class FormBuilder
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Format\Browser\Filter
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class FormBuilder extends AbstractFormBuilder
{

    use DataTableUITrait;

    /**
     * @var Table
     */
    protected $component;
    /**
     * @var Settings
     */
    protected $settings;


    /**
     * @inheritDoc
     *
     * @param BrowserFormat $parent
     * @param array         $filter_fields
     * @param Settings      $settings
     */
    public function __construct(BrowserFormat $parent, Table $component, Settings $settings)
    {
        $this->component = $component;
        $this->settings = $settings;

        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    protected function getAction() : string
    {
        return $this->parent->getActionUrlWithParams($this->component->getActionUrl(), [SettingsStorage::VAR_FILTER_FIELD_VALUES => true], $this->component->getTableId());
    }


    /**
     * @inheritDoc
     */
    protected function getButtons() : array
    {
        $buttons = [];

        return $buttons;
    }


    /**
     * @inheritDoc
     */
    protected function getData() : array
    {
        return $this->settings->getFilterFieldValues();
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        return $this->component->getFilterFields();
    }


    /**
     * @inheritDoc
     */
    protected function getTitle() : string
    {
        return $this->component->getPlugin()->translate("filter", Table::LANG_MODULE);
    }


    /**
     * @inheritDoc
     */
    protected function setButtonsToForm(string $html) : string
    {
        $html = preg_replace_callback(self::REPLACE_BUTTONS_REG_EXP, function (array $matches) : string {
            return self::output()->getHTML([
                self::dic()->ui()->factory()->legacy($matches[1] . $this->component->getPlugin()->translate("apply_filter", Table::LANG_MODULE) . $matches[3] . "&nbsp;"),
                self::dic()->ui()->factory()->button()->standard($this->component->getPlugin()->translate("reset_filter", Table::LANG_MODULE),
                    $this->parent->getActionUrlWithParams($this->component->getActionUrl(), [SettingsStorage::VAR_RESET_FILTER_FIELD_VALUES => true], $this->component->getTableId()))
            ]);
        }, $html);

        return $html;
    }


    /**
     * @inheritDoc
     */
    protected function storeData(array $data)/* : void*/
    {
        $this->settings = $this->settings->withFilterFieldValues($data);
    }


    /**
     * @return Settings
     */
    public function getSettings() : Settings
    {
        return $this->settings;
    }
}