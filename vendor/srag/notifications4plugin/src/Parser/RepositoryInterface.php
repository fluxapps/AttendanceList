<?php

namespace srag\Notifications4Plugin\AttendanceList\Parser;

use srag\Notifications4Plugin\AttendanceList\Exception\Notifications4PluginException;
use srag\Notifications4Plugin\AttendanceList\Notification\NotificationInterface;

/**
 * Interface RepositoryInterface
 *
 * @package srag\Notifications4Plugin\AttendanceList\Parser
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface RepositoryInterface
{

    /**
     * @param Parser $parser
     */
    public function addParser(Parser $parser);


    /**
     * @internal
     */
    public function dropTables();


    /**
     * @return FactoryInterface
     */
    public function factory();


    /**
     * @param string $parser_class
     *
     * @return Parser
     *
     * @throws Notifications4PluginException
     */
    public function getParserByClass($parser_class);


    /**
     * @param NotificationInterface $notification
     *
     * @return Parser
     *
     * @throws Notifications4PluginException
     */
    public function getParserForNotification(NotificationInterface $notification);


    /**
     * @return Parser[]
     */
    public function getPossibleParsers();


    /**
     * @internal
     */
    public function installTables();


    /**
     * @param Parser                $parser
     * @param NotificationInterface $notification
     * @param array                 $placeholders
     * @param string|null           $language
     *
     * @return string
     *
     * @throws Notifications4PluginException
     */
    public function parseSubject(Parser $parser, NotificationInterface $notification, array $placeholders = [], ?string $language = null);


    /**
     * @param Parser                $parser
     * @param NotificationInterface $notification
     * @param array                 $placeholders
     * @param string|null           $language
     *
     * @return string
     *
     * @throws Notifications4PluginException
     */
    public function parseText(Parser $parser, NotificationInterface $notification, array $placeholders = [], ?string $language = null);
}
