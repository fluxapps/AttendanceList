<?php

namespace srag\Notifications4Plugin\AttendanceList\Parser;

/**
 * Interface FactoryInterface
 *
 * @package srag\Notifications4Plugin\AttendanceList\Parser
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface FactoryInterface {

	/**
	 * @return twigParser
	 */
	public function twig();
}
