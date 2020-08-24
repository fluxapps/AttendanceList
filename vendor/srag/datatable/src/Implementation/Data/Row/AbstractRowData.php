<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Data\Row;

use srag\DataTableUI\AttendanceList\Component\Data\Row\RowData;
use srag\DataTableUI\AttendanceList\Implementation\Utils\DataTableUITrait;
use srag\DIC\AttendanceList\DICTrait;

/**
 * Class AbstractRowData
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Data\Row
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractRowData implements RowData
{

    use DICTrait;
    use DataTableUITrait;

    /**
     * @var string
     */
    protected $row_id = "";
    /**
     * @var object
     */
    protected $original_data;


    /**
     * AbstractRowData constructor
     *
     * @param string $row_id
     * @param object $original_data
     */
    public function __construct($row_id, object $original_data)
    {
        $this->row_id = $row_id;
        $this->original_data = $original_data;
    }


    /**
     * @inheritDoc
     */
    public function getRowId()
    {
        return $this->row_id;
    }


    /**
     * @inheritDoc
     */
    public function withRowId($row_id)
    {
        $clone = clone $this;

        $clone->row_id = $row_id;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function getOriginalData()
    {
        return $this->original_data;
    }


    /**
     * @inheritDoc
     */
    public function withOriginalData(object $original_data)
    {
        $clone = clone $this;

        $clone->original_data = $original_data;

        return $clone;
    }
}
