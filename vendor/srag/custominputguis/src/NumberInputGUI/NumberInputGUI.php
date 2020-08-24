<?php

namespace srag\CustomInputGUIs\AttendanceList\NumberInputGUI;

use ilNumberInputGUI;
use ilTableFilterItem;
use ilToolbarItem;
use srag\DIC\AttendanceList\DICTrait;

/**
 * Class NumberInputGUI
 *
 * @package srag\CustomInputGUIs\AttendanceList\NumberInputGUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class NumberInputGUI extends ilNumberInputGUI implements ilTableFilterItem, ilToolbarItem
{

    use DICTrait;

    /**
     * @inheritDoc
     */
    public function getTableFilterHTML()
    {
        return $this->render();
    }


    /**
     * @inheritDoc
     */
    public function getToolbarHTML()
    {
        return $this->render();
    }
}
