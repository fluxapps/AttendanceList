<?php

namespace srag\Notifications4Plugin\AttendanceList\Sender;

use ilObjUser;

/**
 * Interface FactoryInterface
 *
 * @package srag\Notifications4Plugin\AttendanceList\Sender
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface FactoryInterface {

	/**
	 * @param string       $from
	 * @param string|array $to
	 *
	 * @return ExternalMailSender
	 */
	public function externalMail($from = "", $to = "");


	/**
	 * @param int|string|ilObjUser $user_from
	 * @param int|string|ilObjUser $user_to
	 *
	 * @return InternalMailSender
	 */
	public function internalMail($user_from = 0, $user_to = "");


	/**
	 * @param int|string|ilObjUser $user_from
	 * @param string|array         $to
	 * @param string               $method
	 * @param string               $uid
	 * @param int                  $startTime
	 * @param int                  $endTime
	 * @param int                  $sequence
	 *
	 * @return vcalendarSender
	 */
	public function vcalendar($user_from = 0, $to = "", $method = vcalendarSender::METHOD_REQUEST, $uid = "", $startTime = 0, $endTime = 0, $sequence = 0);
}
