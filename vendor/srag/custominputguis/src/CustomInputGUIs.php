<?php

namespace srag\CustomInputGUIs\AttendanceList;

use srag\CustomInputGUIs\AttendanceList\ViewControlModeUI\ViewControlModeUI;
use srag\DIC\AttendanceList\DICTrait;

/**
 * Class CustomInputGUIs
 *
 * @package srag\CustomInputGUIs\AttendanceList
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class CustomInputGUIs
{

    use DICTrait;

    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * CustomInputGUIs constructor
     */
    private function __construct()
    {

    }


    /**
     * @return self
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @return ViewControlModeUI
     */
    public function viewControlMode()
    {
        return new ViewControlModeUI();
    }
}
