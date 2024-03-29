<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Utils;

use srag\DataTableUI\AttendanceList\Component\Table;
use srag\DataTableUI\AttendanceList\Component\Utils\TableBuilder;
use srag\DIC\AttendanceList\DICTrait;

/**
 * Class AbstractTableBuilder
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Utils
 */
abstract class AbstractTableBuilder implements TableBuilder
{

    use DICTrait;
    use DataTableUITrait;

    /**
     * @var object
     */
    protected $parent;
    /**
     * @var Table|null
     */
    protected $table = null;


    /**
     * AbstractTableBuilder constructor
     *
     * @param object $parent
     */
    public function __construct(object $parent)
    {
        $this->parent = $parent;
    }


    /**
     * @inheritDoc
     */
    public function getTable() : Table
    {
        if ($this->table === null) {
            $this->table = $this->buildTable();
        }

        return $this->table;
    }


    /**
     * @inheritDoc
     */
    public function render() : string
    {
        return self::output()->getHTML($this->getTable());
    }


    /**
     * @return Table
     */
    protected abstract function buildTable() : Table;
}
