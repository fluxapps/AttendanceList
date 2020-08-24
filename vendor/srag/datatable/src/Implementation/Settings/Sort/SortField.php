<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Settings\Sort;

use srag\DataTableUI\AttendanceList\Component\Settings\Sort\SortField as SortFieldInterface;
use srag\DataTableUI\AttendanceList\Component\Settings\Storage\SettingsStorage;
use srag\DataTableUI\AttendanceList\Implementation\Utils\DataTableUITrait;
use srag\DIC\AttendanceList\DICTrait;
use stdClass;

/**
 * Class SortField
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Settings\Sort
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class SortField implements SortFieldInterface
{

    use DICTrait;
    use DataTableUITrait;

    /**
     * @var string
     */
    protected $sort_field = "";
    /**
     * @var int
     */
    protected $sort_field_direction = 0;


    /**
     * SortField constructor
     *
     * @param string $sort_field
     * @param int    $sort_field_direction
     */
    public function __construct($sort_field, $sort_field_direction)
    {
        $this->sort_field = $sort_field;

        $this->sort_field_direction = $sort_field_direction;
    }


    /**
     * @inheritDoc
     */
    public function getSortField()
    {
        return $this->sort_field;
    }


    /**
     * @inheritDoc
     */
    public function withSortField($sort_field)
    {
        $clone = clone $this;

        $clone->sort_field = $sort_field;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function getSortFieldDirection()
    {
        return $this->sort_field_direction;
    }


    /**
     * @inheritDoc
     */
    public function withSortFieldDirection($sort_field_direction)
    {
        $clone = clone $this;

        $clone->sort_field_direction = $sort_field_direction;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return (object) [
            SettingsStorage::VAR_SORT_FIELD           => $this->sort_field,
            SettingsStorage::VAR_SORT_FIELD_DIRECTION => $this->sort_field_direction
        ];
    }
}
