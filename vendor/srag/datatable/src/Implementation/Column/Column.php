<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Column;

use srag\DataTableUI\AttendanceList\Component\Column\Column as ColumnInterface;
use srag\DataTableUI\AttendanceList\Component\Column\Formatter\Actions\ActionsFormatter;
use srag\DataTableUI\AttendanceList\Component\Column\Formatter\Formatter;
use srag\DataTableUI\AttendanceList\Component\Settings\Sort\SortField;
use srag\DataTableUI\AttendanceList\Implementation\Utils\DataTableUITrait;
use srag\DIC\AttendanceList\DICTrait;

/**
 * Class Column
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Column
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Column implements ColumnInterface
{

    use DICTrait;
    use DataTableUITrait;

    /**
     * @var string
     */
    protected $key = "";
    /**
     * @var string
     */
    protected $title = "";
    /**
     * @var Formatter
     */
    protected $formatter;
    /**
     * @var bool
     */
    protected $sortable = true;
    /**
     * @var bool
     */
    protected $default_sort = false;
    /**
     * @var int
     */
    protected $default_sort_direction = SortField::SORT_DIRECTION_UP;
    /**
     * @var bool
     */
    protected $selectable = true;
    /**
     * @var bool
     */
    protected $default_selected = true;
    /**
     * @var bool
     */
    protected $exportable = true;


    /**
     * @inheritDoc
     */
    public function __construct($key, $title)
    {
        $this->key = $key;

        $this->title = $title;
    }


    /**
     * @inheritDoc
     */
    public function getKey()
    {
        return $this->key;
    }


    /**
     * @inheritDoc
     */
    public function withKey($key)
    {
        $clone = clone $this;

        $clone->key = $key;

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
    public function getFormatter()
    {
        if ($this->formatter === null) {
            $this->formatter = self::dataTableUI()->column()->formatter()->default();
        }

        return $this->formatter;
    }


    /**
     * @inheritDoc
     */
    public function withFormatter(Formatter $formatter)
    {
        $clone = clone $this;

        $clone->formatter = $formatter;

        if ($clone->formatter instanceof ActionsFormatter) {
            $clone->sortable = false;
            $clone->selectable = false;
            $clone->exportable = false;
        }

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function isSortable()
    {
        return $this->sortable;
    }


    /**
     * @inheritDoc
     */
    public function withSortable($sortable = true)
    {
        $clone = clone $this;

        $clone->sortable = $sortable;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function isDefaultSort()
    {
        return $this->default_sort;
    }


    /**
     * @inheritDoc
     */
    public function withDefaultSort($default_sort = false)
    {
        $clone = clone $this;

        $clone->default_sort = $default_sort;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function getDefaultSortDirection()
    {
        return $this->default_sort_direction;
    }


    /**
     * @inheritDoc
     */
    public function withDefaultSortDirection($default_sort_direction = SortField::SORT_DIRECTION_UP)
    {
        $clone = clone $this;

        $clone->default_sort_direction = $default_sort_direction;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function isSelectable()
    {
        return $this->selectable;
    }


    /**
     * @inheritDoc
     */
    public function withSelectable($selectable = true)
    {
        $clone = clone $this;

        $clone->selectable = $selectable;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function isDefaultSelected()
    {
        return $this->default_selected;
    }


    /**
     * @inheritDoc
     */
    public function withDefaultSelected($default_selected = true)
    {
        $clone = clone $this;

        $clone->default_selected = $default_selected;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function isExportable()
    {
        return $this->exportable;
    }


    /**
     * @inheritDoc
     */
    public function withExportable($exportable = true)
    {
        $clone = clone $this;

        $clone->exportable = $exportable;

        return $clone;
    }
}
