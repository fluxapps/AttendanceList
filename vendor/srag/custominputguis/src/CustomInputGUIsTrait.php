<?php

namespace srag\CustomInputGUIs\AttendanceList;

/**
 * Trait CustomInputGUIsTrait
 *
 * @package srag\CustomInputGUIs\AttendanceList
 */
trait CustomInputGUIsTrait
{

    /**
     * @return CustomInputGUIs
     */
    protected static final function customInputGUIs() : CustomInputGUIs
    {
        return CustomInputGUIs::getInstance();
    }
}
