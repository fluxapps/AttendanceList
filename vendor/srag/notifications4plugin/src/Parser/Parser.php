<?php

namespace srag\Notifications4Plugin\AttendanceList\Parser;

use ILIAS\UI\Component\Input\Field\Input;
use srag\Notifications4Plugin\AttendanceList\Exception\Notifications4PluginException;

/**
 * Interface Parser
 *
 * @package srag\Notifications4Plugin\AttendanceList\Parser
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
interface Parser
{

    /**
     * @var string
     *
     * @abstract
     */
    const DOC_LINK = "";
    /**
     * @var string
     *
     * @abstract
     */
    const NAME = "";


    /**
     * @return string
     */
    public function getClass();


    /**
     * @return string
     */
    public function getDocLink();


    /**
     * @return string
     */
    public function getName();


    /**
     * @return Input[]
     */
    public function getOptionsFields();


    /**
     * @param string $text
     * @param array  $placeholders
     * @param array  $options
     *
     * @return string
     *
     * @throws Notifications4PluginException
     */
    public function parse($text, array $placeholders = [], array $options = []);
}
