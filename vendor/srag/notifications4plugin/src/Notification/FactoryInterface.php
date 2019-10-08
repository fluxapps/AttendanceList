<?php

namespace srag\Notifications4Plugin\AttendanceList\Notification;

use stdClass;

/**
 * Interface FactoryInterface
 *
 * @package srag\Notifications4Plugin\AttendanceList\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface FactoryInterface {

	/**
	 * @param stdClass $data
	 *
	 * @return Notification
	 */
	public function fromDB(stdClass $data);


	/**
	 * @return Notification
	 */
	public function newInstance();
}
