<?php

namespace srag\Notifications4Plugin\AttendanceList\Parser;

use srag\DIC\AttendanceList\DICTrait;
use srag\Notifications4Plugin\AttendanceList\Utils\Notifications4PluginTrait;

/**
 * Class AbstractParser
 *
 * @package srag\Notifications4Plugin\AttendanceList\Parser
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractParser implements Parser
{

    use DICTrait;
    use Notifications4PluginTrait;

    /**
     * AbstractParser constructor
     */
    public function __construct()
    {

    }


    /**
     * @inheritDoc
     */
    public function getClass()
    {
        return static::class;
    }


    /**
     * @inheritDoc
     */
    public function getDocLink()
    {
        return static::DOC_LINK;
    }


    /**
     * @inheritDoc
     */
    public function getName()
    {
        return static::NAME;
    }


    /**
     * @param string $html
     *
     * @return string
     */
    protected function fixLineBreaks($html)
    {
        return str_ireplace(["&lt;br&gt;", "&lt;br/&gt;", "&lt;br /&gt;"], ["<br>", "<br/>", "<br />"], $html);
    }
}
