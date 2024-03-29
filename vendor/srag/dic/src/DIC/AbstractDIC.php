<?php

namespace srag\DIC\AttendanceList\DIC;

use ILIAS\DI\Container;
use srag\DIC\AttendanceList\Database\DatabaseDetector;
use srag\DIC\AttendanceList\Database\DatabaseInterface;

/**
 * Class AbstractDIC
 *
 * @package srag\DIC\AttendanceList\DIC
 */
abstract class AbstractDIC implements DICInterface
{

    /**
     * @var Container
     */
    protected $dic;


    /**
     * @inheritDoc
     */
    public function __construct(Container &$dic)
    {
        $this->dic = &$dic;
    }


    /**
     * @inheritDoc
     */
    public function database() : DatabaseInterface
    {
        return DatabaseDetector::getInstance($this->databaseCore());
    }
}
