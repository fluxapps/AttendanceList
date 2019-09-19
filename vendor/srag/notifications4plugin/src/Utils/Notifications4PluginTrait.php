<?php

namespace srag\Notifications4Plugin\AttendanceList\Utils;

use srag\Notifications4Plugin\AttendanceList\Notification\Language\Repository as NotificationLanguageRepository;
use srag\Notifications4Plugin\AttendanceList\Notification\Language\RepositoryInterface as NotificationLanguageRepositoryInterface;
use srag\Notifications4Plugin\AttendanceList\Notification\Repository as NotificationRepository;
use srag\Notifications4Plugin\AttendanceList\Notification\RepositoryInterface as NotificationRepositoryInterface;
use srag\Notifications4Plugin\AttendanceList\Parser\Repository as ParserRepository;
use srag\Notifications4Plugin\AttendanceList\Parser\RepositoryInterface as ParserRepositoryInterface;
use srag\Notifications4Plugin\AttendanceList\Sender\Repository as SenderRepository;
use srag\Notifications4Plugin\AttendanceList\Sender\RepositoryInterface as SenderRepositoryInterface;
use srag\Notifications4Plugin\AttendanceList\UI\UI;
use srag\Notifications4Plugin\AttendanceList\UI\UIInterface;

/**
 * Trait Notifications4PluginTrait
 *
 * @package srag\Notifications4Plugin\AttendanceList\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
trait Notifications4PluginTrait {

	/**
	 * @param string $notification_class
	 * @param string $language_class
	 *
	 * @return NotificationRepositoryInterface
	 */
	protected static function notification($notification_class, $language_class) {
		return NotificationRepository::getInstance($notification_class, $language_class);
	}


	/**
	 * @param string $language_class
	 *
	 * @return NotificationLanguageRepositoryInterface
	 */
	protected static function notificationLanguage($language_class) {
		return NotificationLanguageRepository::getInstance($language_class);
	}


	/**
	 * @return UIInterface
	 */
	protected static function notificationUI() {
		return UI::getInstance();
	}


	/**
	 * @return ParserRepositoryInterface
	 */
	protected static function parser() {
		return ParserRepository::getInstance();
	}


	/**
	 * @return SenderRepositoryInterface
	 */
	protected static function sender() {
		return SenderRepository::getInstance();
	}
}
