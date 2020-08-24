<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Settings;

use ILIAS\UI\Component\ViewControl\Pagination;
use ILIAS\UI\Implementation\Component\ComponentHelper;
use srag\DataTableUI\AttendanceList\Component\Data\Data;
use srag\DataTableUI\AttendanceList\Component\Settings\Settings as SettingsInterface;
use srag\DataTableUI\AttendanceList\Component\Settings\Sort\SortField;
use srag\DataTableUI\AttendanceList\Implementation\Utils\DataTableUITrait;
use srag\DIC\AttendanceList\DICTrait;

/**
 * Class Settings
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Settings
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Settings implements SettingsInterface
{

    use ComponentHelper;
    use DICTrait;
    use DataTableUITrait;

    /**
     * @var Pagination
     */
    protected $pagination;
    /**
     * @var mixed[]
     */
    protected $filter_field_values = [];
    /**
     * @var SortField[]
     */
    protected $sort_fields = [];
    /**
     * @var string[]
     */
    protected $selected_columns = [];
    /**
     * @var bool
     */
    protected $filter_set = false;


    /**
     * Settings constructor
     *
     * @param Pagination $pagination
     */
    public function __construct(Pagination $pagination)
    {
        $this->pagination = $pagination->withPageSize(self::DEFAULT_ROWS_COUNT);
    }


    /**
     * @inheritDoc
     */
    public function getFilterFieldValues()
    {
        return $this->filter_field_values;
    }


    /**
     * @inheritDoc
     */
    public function getFilterFieldValue($key)
    {
        return isset($this->filter_field_values[$key]) ? $this->filter_field_values[$key] : null;
    }


    /**
     * @inheritDoc
     */
    public function withFilterFieldValues(array $filter_field_values)
    {
        $clone = clone $this;

        $clone->filter_field_values = $filter_field_values;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function getSortFields()
    {
        return $this->sort_fields;
    }


    /**
     * @inheritDoc
     */
    public function getSortField($sort_field)
    {
        $sort_field = current(array_filter($this->sort_fields, function (SortField $sort_field_) use($sort_field) {    return $sort_field_->getSortField() === $sort_field;
}));

        if ($sort_field !== false) {
            return $sort_field;
        } else {
            return null;
        }
    }


    /**
     * @inheritDoc
     */
    public function withSortFields(array $sort_fields)
    {
        $classes = [SortField::class];
        $this->checkArgListElements("sort_fields", $sort_fields, $classes);

        $clone = clone $this;

        $clone->sort_fields = $sort_fields;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function addSortField(SortField $sort_field)
    {
        $clone = clone $this;

        if ($this->getSortField($sort_field->getSortField()) !== null) {
            $clone->sort_fields = array_reduce($clone->sort_fields, function (array $sort_fields, SortField $sort_field_) use($sort_field) {
    if ($sort_field_->getSortField() === $sort_field->getSortField()) {
        $sort_field_ = $sort_field;
    }
    $sort_fields[] = $sort_field_;
    return $sort_fields;
}, []);
        } else {
            $clone->sort_fields[] = $sort_field;
        }

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function removeSortField($sort_field)
    {
        $clone = clone $this;

        $clone->sort_fields = array_values(array_filter($clone->sort_fields, function (SortField $sort_field_) use($sort_field) {
    return $sort_field_->getSortField() !== $sort_field;
}));

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function getSelectedColumns()
    {
        return $this->selected_columns;
    }


    /**
     * @inheritDoc
     */
    public function withSelectedColumns(array $selected_columns)
    {
        $clone = clone $this;

        $clone->selected_columns = $selected_columns;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function selectColumn($selected_column)
    {
        $clone = clone $this;

        if (!in_array($selected_column, $clone->selected_columns)) {
            $clone->selected_columns[] = $selected_column;
        }

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function deselectColumn($selected_column)
    {
        $clone = clone $this;

        $clone->selected_columns = array_values(array_filter($clone->selected_columns, function ($selected_column_) use($selected_column) {
    return $selected_column_ !== $selected_column;
}));

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function isFilterSet()
    {
        return $this->filter_set;
    }


    /**
     * @inheritDoc
     */
    public function withFilterSet($filter_set = false)
    {
        $clone = clone $this;

        $clone->filter_set = $filter_set;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function getRowsCount()
    {
        return $this->pagination->getPageSize();
    }


    /**
     * @inheritDoc
     */
    public function withRowsCount($rows_count = self::DEFAULT_ROWS_COUNT)
    {
        $clone = clone $this;

        $clone->pagination = $clone->pagination->withPageSize($rows_count);

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function getCurrentPage()
    {
        return $this->pagination->getCurrentPage();
    }


    /**
     * @inheritDoc
     */
    public function withCurrentPage($current_page = 0)
    {
        $clone = clone $this;

        $clone->pagination = $clone->pagination->withCurrentPage($current_page);

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function getOffset()
    {
        return $this->pagination->getOffset();
    }


    /**
     * @inheritDoc
     *
     * @internal
     */
    public function getPagination(?Data $data)
    {
        return $this->pagination->withTotalEntries($data === null ? 0 : $data->getMaxCount());
    }
}
