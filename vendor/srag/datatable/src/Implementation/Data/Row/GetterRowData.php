<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Data\Row;

use srag\CustomInputGUIs\AttendanceList\PropertyFormGUI\Items\Items;

/**
 * Class GetterRowData
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Data\Row
 */
class GetterRowData extends AbstractRowData
{

    /**
     * @inheritDoc
     */
    public function __invoke(string $key)
    {
        return Items::getter($this->getOriginalData(), $key);
    }
}
