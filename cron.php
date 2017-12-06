<?php
$cron = new xaliCron($_SERVER['argv']);
$cron->run();

/**
 * Class xaliCron
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xaliCron {

	const DEBUG = false;
	/**
	 * @var Ilias
	 */
	protected $ilias;
	/**
	 * @var ilAttendanceListPlugin
	 */
	protected $pl;
	/**
	 * @var ilLog
	 */
	protected $log;


	/**
	 * @param array $data
	 */
	function __construct($data) {
		$_COOKIE['ilClientId'] = $data[3];
		$_POST['username'] = $data[1];
		$_POST['password'] = $data[2];
		$this->initILIAS();

		global $ilDB, $ilUser, $ilCtrl, $ilLog, $ilias;
		if (self::DEBUG) {
			$ilLog->write('Auth passed for async AttendanceList');
		}

		require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/classes/class.ilAttendanceListPlugin.php';
		/**
		 * @var $ilDB   ilDB
		 * @var $ilUser ilObjUser
		 * @var $ilCtrl ilCtrl
		 */
		$this->pl = ilAttendanceListPlugin::getInstance();
		$this->db = $ilDB;
		$this->user = $ilUser;
		$this->ctrl = $ilCtrl;
		$this->ilias = $ilias;
		$this->log = $ilLog;
	}


	public function initILIAS() {
		chdir(substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], '/Customizing')));
		require_once('include/inc.ilias_version.php');
		require_once('Services/Component/classes/class.ilComponent.php');
		if (ilComponent::isVersionGreaterString(ILIAS_VERSION_NUMERIC, '5.1.999')) {
			require_once './Services/Cron/classes/class.ilCronStartUp.php';
			$ilCronStartup = new ilCronStartUp($_SERVER['argv'][3], $_SERVER['argv'][1], $_SERVER['argv'][2]);
			$ilCronStartup->initIlias();
			$ilCronStartup->authenticate();
		} else {
			require_once "Services/Context/classes/class.ilContext.php";
			ilContext::init(ilContext::CONTEXT_CRON);
			require_once 'Services/Authentication/classes/class.ilAuthFactory.php';
			ilAuthFactory::setContext(ilAuthFactory::CONTEXT_CRON);
			require_once './include/inc.header.php';
		}


		// fix for some stupid ilias init....
		global $ilSetting;
		if (!$ilSetting) {
			$ilSetting = new ilSessionMock();
		}
	}

	/**
	 *
	 */
	public function run() {
		require_once 'Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/Notifications4Plugins/classes/NotificationSender/class.srNotificationInternalMailSender.php';
		require_once 'Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/Notifications4Plugins/classes/Notification/class.srNotification.php';
		require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/classes/Config/class.xaliConfig.php';
		require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/classes/Config/class.xaliLastReminder.php';
		require_once 'Services/User/classes/class.ilObjUser.php';

		$interval = xaliConfig::getConfig(xaliConfig::F_INTERVAL_REMINDER_EMAIL);
		if (!$interval) {
			return true;
		}

		$send_mail = array();

		$time_string = date('Y-m-d');

		// fetch open absence statements for checklists which are online
		// TODO activation_to + 30 tage > now ?? oder bis kurs offline / gelöscht ist (geht wahrsch. nicht, weil alte Kurse noch online sind)?
		$query = "
			SELECT 
			    xali_entry.*, object_reference.ref_id, xali_checklist.checklist_date
			FROM
			    xali_data
				    INNER JOIN 
				object_reference on xali_data.id = object_reference.obj_id
			        INNER JOIN
			    xali_checklist ON xali_checklist.obj_id = xali_data.id
					INNER JOIN 
				xali_entry ON xali_entry.checklist_id = xali_checklist.id
					LEFT JOIN
				xali_absence_statement ON xali_absence_statement.entry_id = xali_entry.id
			WHERE
			    xali_data.is_online = 1
			        AND xali_data.activation_from < '$time_string'
			        AND xali_data.activation_to > '2017-12-06'
					AND xali_entry.status = 1
					AND xali_absence_statement.entry_id IS NULL;";

		$sql = $this->db->query($query);
		// array format:
		// [ user_id =>
		//      [ ref_id =>
		//          [ entry_id => checklist_date ]
		//      ]
		// ]
		while ($res = $this->db->fetchAssoc($sql)) {
			if (!is_array($send_mail[$res['user_id']])) {
				$send_mail[$res['user_id']] = array();
			}
			if (!is_array($send_mail[$res['user_id']][$res['ref_id']])) {
				$sender_id[$res['user_id']][$res['ref_id']] = array();
			}
			$send_mail[$res['user_id']][$res['ref_id']][$res['id']] = $res['checklist_date'];
		}

		// send mails
		foreach ($send_mail as $user_id => $array) {
			/** @var xaliLastReminder $last_reminder */
			$last_reminder = xaliLastReminder::where(array('user_id' => $user_id))->first();

			if (!$last_reminder) {
				$last_reminder = new xaliLastReminder();
				$last_reminder->setUserId($user_id);
				$last_reminder->create();
			}


			if ($last_reminder->getLastReminder() >= date('Y-m-d', strtotime("now -$interval days"))) {
				$this->log->write('continue');
				continue;
			}

			$this->log->write('send');
			$ilObjUser = new ilObjUser($user_id);
			$sender_id = xaliConfig::getConfig(xaliConfig::F_SENDER_REMINDER_EMAIL);
			$sender = new srNotificationInternalMailSender($sender_id,$user_id);

			$open_absences = '';
			foreach ($array as $ref_id => $entry_array) {
				$this->ctrl->setParameterByClass(xaliAbsenceStatementGUI::class, 'ref_id', $ref_id);
				$base_link_relative = $this->ctrl->getLinkTargetByClass(array(ilObjPluginDispatchGUI::class, ilObjAttendanceListGUI::class, xaliAbsenceStatementGUI::class), xaliAbsenceStatementGUI::CMD_STANDARD);
				$base_link = xaliConfig::getConfig(xaliConfig::F_HTTP_PATH) . '/ilias.php' . $base_link_relative . '&baseClass=ilObjPluginDispatchGUI';

				$parent_course = $this->pl->getParentCourseOrGroup($ref_id);
				$open_absences .= 'Kurs "' . $parent_course->getTitle() . "\": \n";
				foreach ($entry_array as $entry_id => $checklist_date) {
					$open_absences .= "- $checklist_date: " . $base_link . "&entry_id=$entry_id \n";
				}
				$open_absences .= "\n";
			}
			$placeholders = array('user' => $ilObjUser, 'open_absences' => $open_absences);

			$notification = srNotification::getInstanceByName('absence_reminder');
			$sent = $notification->send($sender, $placeholders);

			if ($sent) {
				$last_reminder->setLastReminder(date('Y-m-d'));
				$last_reminder->update();
			}
		}

	}


}


class ilSessionMock {
	public function get($what, $default) {
		return $default;
	}

}