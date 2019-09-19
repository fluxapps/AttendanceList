<?php

namespace srag\Notifications4Plugin\AttendanceList\Parser;

use srag\Notifications4Plugin\AttendanceList\Exception\Notifications4PluginException;

/**
 * Interface Parser
 *
 * @package srag\Notifications4Plugin\AttendanceList\Parser
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
interface Parser {

	/**
	 * @var string
	 *
	 * @abstract
	 */
	//const NAME = "";

	/**
	 * @param string $text
	 * @param array  $placeholders
	 *
	 * @return string
	 *
	 * @throws Notifications4PluginException
	 */
	public function parse($text, array $placeholders = []);
}
