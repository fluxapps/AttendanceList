<?php

namespace srag\Plugins\AttendanceList\Notification\Ctrl;

use ilAttendanceListConfigGUI;
use ilAttendanceListPlugin;
use ilObjUser;
use srag\Notifications4Plugin\AttendanceList\Ctrl\AbstractCtrl;
use srag\Plugins\AttendanceList\Notification\Notification\Language\NotificationLanguage;
use srag\Plugins\AttendanceList\Notification\Notification\Notification;
use xaliChecklistEntry;
use xaliCron;

/**
 * Class Notifications4PluginCtrl
 *
 * @package           srag\Plugins\AttendanceList\Notification\Ctrl
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\AttendanceList\Notification\Ctrl\Notifications4PluginCtrl: ilAttendanceListConfigGUI
 */
class Notifications4PluginCtrl extends AbstractCtrl {

	const NOTIFICATION_CLASS_NAME = Notification::class;
	const LANGUAGE_CLASS_NAME = NotificationLanguage::class;
	const PLUGIN_CLASS_NAME = ilAttendanceListPlugin::class;


	/**
	 * @inheritdoc
	 */
	public function executeCommand()/*: void*/ {
		$notification = $this->getNotification();

		if ($notification !== null) {
			switch ($notification->getName()) {
				case xaliChecklistEntry::NOTIFICATION_NAME:
					self::dic()->tabs()->activateSubTab(ilAttendanceListConfigGUI::SUBTAB_NOTIFICATION_ABSENCE);
					break;

				case xaliCron::NOTIFICATION_NAME:
					self::dic()->tabs()->activateSubTab(ilAttendanceListConfigGUI::SUBTAB_NOTIFICATION_ABSENCE_REMINDER);
					break;

				default:
					break;
			}
		}

		parent::executeCommand();
	}


	/**
	 * @inheritdoc
	 */
	protected function listNotifications()/*: void*/ {
		self::dic()->ctrl()->redirectByClass(ilAttendanceListConfigGUI::class);
	}


	/**
	 * @inheritdoc
	 */
	public function getPlaceholderTypes()/*: array*/ {
		$notification = $this->getNotification();

		if ($notification !== null) {
			switch ($notification->getName()) {
				case xaliChecklistEntry::NOTIFICATION_NAME:
					return [
						'user' => 'object ' . ilObjUser::class,
						'absence' => 'string'
					];

				case xaliCron::NOTIFICATION_NAME:
					return [
						'user' => 'object ' . ilObjUser::class,
						'open_absences' => 'string'
					];

				default:
					break;
			}
		}

		return [];
	}
}
