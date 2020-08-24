<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Data\Row;

use srag\CustomInputGUIs\AttendanceList\PropertyFormGUI\Items\Items;

/**
 * Class GetterRowData
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Data\Row
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class GetterRowData extends AbstractRowData
{

    /**
     * @inheritDoc
     */
    public function __invoke($key)
    {
        return Items::getter($this->getOriginalData(), $key);
    }
}
