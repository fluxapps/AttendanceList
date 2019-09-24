<?php

namespace srag\Notifications4Plugin\AttendanceList\UI;

use ilConfirmationGUI;
use srag\DIC\AttendanceList\Plugin\Pluginable;
use srag\Notifications4Plugin\AttendanceList\Ctrl\CtrlInterface;
use srag\Notifications4Plugin\AttendanceList\Notification\Notification;

/**
 * Interface UIInterface
 *
 * @package srag\Notifications4Plugin\AttendanceList\UI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface UIInterface extends Pluginable {

	/**
	 * @param CtrlInterface $ctrl_class
	 *
	 * @return self
	 */
	public function withCtrlClass(CtrlInterface $ctrl_class);


	/**
	 * @param Notification $notification
	 *
	 * @return ilConfirmationGUI
	 */
	public function notificationDeleteConfirmation(Notification $notification);


	/**
	 * @param Notification $notification
	 *
	 * @return NotificationFormGUI
	 */
	public function notificationForm(Notification $notification);


	/**
	 * @param string   $parent_cmd
	 * @param callable $getNotifications
	 * @param callable $getNotificationsCount
	 *
	 * @return NotificationsTableGUI
	 */
	public function notificationTable($parent_cmd, callable $getNotifications, callable $getNotificationsCount);


	/**
	 * @param array  $notifications
	 * @param string $post_key
	 * @param bool   $required
	 *
	 * @return array
	 */
	public function templateSelection(array $notifications, $post_key, $required = true);
}
