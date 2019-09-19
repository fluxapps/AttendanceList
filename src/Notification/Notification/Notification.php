<?php

namespace srag\Plugins\AttendanceList\Notification\Notification;

use srag\Notifications4Plugin\AttendanceList\Notification\AbstractNotification;
use srag\Plugins\AttendanceList\Notification\Notification\Language\NotificationLanguage;

/**
 * Class Notification
 *
 * @package srag\Plugins\AttendanceList\Notification\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Notification extends AbstractNotification {

	const TABLE_NAME = "xali_not";
	const LANGUAGE_CLASS_NAME = NotificationLanguage::class;
}
