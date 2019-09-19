<?php

namespace srag\Notifications4Plugin\AttendanceList\Parser;

use srag\Notifications4Plugin\AttendanceList\Exception\Notifications4PluginException;
use srag\Notifications4Plugin\AttendanceList\Notification\Notification;

/**
 * Interface RepositoryInterface
 *
 * @package srag\Notifications4Plugin\AttendanceList\Parser
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface RepositoryInterface {

	/**
	 * @param Parser $parser
	 */
	public function addParser(Parser $parser);


	/**
	 * @return FactoryInterface
	 */
	public function factory();


	/**
	 * @return Parser[]
	 */
	public function getPossibleParsers();


	/**
	 * @param string $parser_class
	 *
	 * @return Parser
	 *
	 * @throws Notifications4PluginException
	 */
	public function getParserByClass($parser_class);


	/**
	 * @param Notification $notification
	 *
	 * @return Parser
	 *
	 * @throws Notifications4PluginException
	 */
	public function getParserForNotification(Notification $notification);


	/**
	 * @param Parser       $parser
	 * @param Notification $notification
	 * @param array        $placeholders
	 * @param string       $language
	 *
	 * @return string
	 *
	 * @throws Notifications4PluginException
	 */
	public function parseSubject(Parser $parser, Notification $notification, array $placeholders = [], $language = "");


	/**
	 * @param Parser       $parser
	 * @param Notification $notification
	 * @param array        $placeholders
	 * @param string       $language
	 *
	 * @return string
	 *
	 * @throws Notifications4PluginException
	 */
	public function parseText(Parser $parser, Notification $notification, array $placeholders = [], $language = "");
}
