<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Data;

use ILIAS\UI\Implementation\Component\ComponentHelper;
use srag\DataTableUI\AttendanceList\Component\Data\Data as DataInterface;
use srag\DataTableUI\AttendanceList\Component\Data\Row\RowData;
use srag\DataTableUI\AttendanceList\Implementation\Utils\DataTableUITrait;
use srag\DIC\AttendanceList\DICTrait;

/**
 * Class Data
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Data
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Data implements DataInterface
{

    use ComponentHelper;
    use DICTrait;
    use DataTableUITrait;

    /**
     * @var RowData[]
     */
    protected $data = [];
    /**
     * @var int
     */
    protected $max_count = 0;


    /**
     * Data constructor
     *
     * @param RowData[] $data
     * @param int       $max_count
     */
    public function __construct(array $data, $max_count)
    {
        $this->data = $data;

        $this->max_count = $max_count;
    }


    /**
     * @inheritDoc
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     * @inheritDoc
     */
    public function withData(array $data)
    {
        $classes = [RowData::class];
        $this->checkArgListElements("data", $data, $classes);

        $clone = clone $this;

        $clone->data = $data;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function getMaxCount()
    {
        return $this->max_count;
    }


    /**
     * @inheritDoc
     */
    public function withMaxCount($max_count)
    {
        $clone = clone $this;

        $clone->max_count = $max_count;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function getDataCount()
    {
        return count($this->data);
    }
}
