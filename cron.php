<?php
$cron = new xaliCron($_SERVER['argv']);
$cron->run();
$cron->logout();

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
	 * @var ilRbacReview
	 */
	protected $rbacreview;


	/**
	 * @param array $data
	 */
	function __construct($data) {
		$_COOKIE['ilClientId'] = $data[3];
		$_POST['username'] = $data[1];
		$_POST['password'] = $data[2];
		$this->initILIAS();

		global $DIC;
		$ilDB = $DIC['ilDB'];
		$ilUser = $DIC['ilUser'];
		$ilCtrl = $DIC['ilCtrl'];
		$ilLog = $DIC['ilLog'];
		$rbacreview = $DIC['rbacreview'];
		if (self::DEBUG) {
			$ilLog->write('Auth passed for async AttendanceList');
		}

		require_once __DIR__ . '/classes/class.ilAttendanceListPlugin.php';
		/**
		 * @var $ilDB   ilDB
		 * @var $ilUser ilObjUser
		 * @var $ilCtrl ilCtrl
		 */
		$this->pl = ilAttendanceListPlugin::getInstance();
		$this->db = $ilDB;
		$this->user = $ilUser;
		$this->ctrl = $ilCtrl;
		$this->log = $ilLog;
		$this->rbacreview = $rbacreview;
	}


	public function initILIAS() {
		chdir(substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], '/Customizing')));
		require_once 'include/inc.ilias_version.php';
		require_once 'Services/Component/classes/class.ilComponent.php';
		if (ilComponent::isVersionGreaterString(ILIAS_VERSION_NUMERIC, '5.1.999')) {
			require_once './Services/Cron/classes/class.ilCronStartUp.php';
			$ilCronStartup = new ilCronStartUp($_SERVER['argv'][3], $_SERVER['argv'][1], $_SERVER['argv'][2]);
			$ilCronStartup->initIlias();
			$ilCronStartup->authenticate();
		} else {
			require_once 'Services/Context/classes/class.ilContext.php';
			ilContext::init(ilContext::CONTEXT_CRON);
			require_once 'Services/Authentication/classes/class.ilAuthFactory.php';
			ilAuthFactory::setContext(ilAuthFactory::CONTEXT_CRON);
			require_once './include/inc.header.php';
		}

		// fix for some stupid ilias init....
		global $DIC;
		$ilSetting = $DIC['ilSetting'];
		if (!$ilSetting) {
			$ilSetting = new ilSessionMock();
		}
	}


	/**
	 *
	 */
	public function run() {
		require_once __DIR__
			. '/../../../UIComponent/UserInterfaceHook/Notifications4Plugins/classes/NotificationSender/class.srNotificationInternalMailSender.php';
		require_once __DIR__ . '/../../../UIComponent/UserInterfaceHook/Notifications4Plugins/classes/Notification/class.srNotification.php';
		require_once __DIR__ . '/classes/Config/class.xaliConfig.php';
		require_once __DIR__ . '/classes/Config/class.xaliLastReminder.php';
		require_once 'Services/User/classes/class.ilObjUser.php';

		$interval = xaliConfig::getConfig(xaliConfig::F_INTERVAL_REMINDER_EMAIL);
		if (!$interval) {
			return true;
		}

		$send_mail = array();

		$now = date('Y-m-d');
		$now_minus_30_days = date('Y-m-d', strtotime('-30 days'));

		// fetch open absence statements for checklists which are online
		// TODO activation_to + 30 tage > now ?? oder bis kurs offline / gelöscht ist (geht wahrsch. nicht, weil alte Kurse noch online sind)?
		$query = "
			SELECT 
			    " . xaliChecklistEntry::DB_TABLE_NAME . ".*, object_reference.ref_id, " . xaliChecklist::DB_TABLE_NAME . ".checklist_date
			FROM
			    " . xaliSetting::DB_TABLE_NAME . "
				    INNER JOIN 
				object_reference on " . xaliSetting::DB_TABLE_NAME . ".id = object_reference.obj_id
			        INNER JOIN
			    " . xaliChecklist::DB_TABLE_NAME . " ON " . xaliChecklist::DB_TABLE_NAME . ".obj_id = " . xaliSetting::DB_TABLE_NAME . ".id
					INNER JOIN 
				" . xaliChecklistEntry::DB_TABLE_NAME . " ON " . xaliChecklistEntry::DB_TABLE_NAME . ".checklist_id = " . xaliChecklist::DB_TABLE_NAME
			. ".id
					LEFT JOIN
				" . xaliAbsenceStatement::TABLE_NAME . " ON " . xaliAbsenceStatement::TABLE_NAME . ".entry_id = " . xaliChecklistEntry::DB_TABLE_NAME
			. ".id
			WHERE
			    " . xaliSetting::DB_TABLE_NAME . ".is_online = 1
			        AND " . xaliSetting::DB_TABLE_NAME . ".activation_from <= '$now'
			        AND " . xaliSetting::DB_TABLE_NAME . ".activation_to > '$now_minus_30_days'
					AND " . xaliChecklistEntry::DB_TABLE_NAME . ".status = 1
					AND " . xaliAbsenceStatement::TABLE_NAME . ".entry_id IS NULL
					AND object_reference.deleted IS NULL;";

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
			$last_reminder = xaliLastReminder::where(array( 'user_id' => $user_id ))->first();

			if (!$last_reminder) {
				$last_reminder = new xaliLastReminder();
				$last_reminder->setUserId($user_id);
				$last_reminder->create();
			}

			if ($last_reminder->getLastReminder() > date('Y-m-d', strtotime("now -$interval days"))) {
				continue;
			}

			$ilObjUser = new ilObjUser($user_id);
			$sender_id = xaliConfig::getConfig(xaliConfig::F_SENDER_REMINDER_EMAIL);
			$sender = new srNotificationInternalMailSender($sender_id, $user_id);

			$open_absences = '';
			foreach ($array as $ref_id => $entry_array) {
				$parent_course = $this->pl->getParentCourseOrGroup($ref_id);

				// check if user is still assigned to course
				if (!$this->rbacreview->isAssigned($user_id, $parent_course->getDefaultMemberRole())) {
					continue;
				}

				$this->ctrl->setParameterByClass(xaliAbsenceStatementGUI::class, 'ref_id', $ref_id);
				$base_link_relative = $this->ctrl->getLinkTargetByClass(array(
					ilObjPluginDispatchGUI::class,
					ilObjAttendanceListGUI::class,
					xaliAbsenceStatementGUI::class
				), xaliAbsenceStatementGUI::CMD_STANDARD);
				$base_link = xaliConfig::getConfig(xaliConfig::F_HTTP_PATH) . '/ilias.php' . $base_link_relative
					. '&baseClass=ilObjPluginDispatchGUI';

				$open_absences .= 'Kurs "' . $parent_course->getTitle() . "\": \n";
				foreach ($entry_array as $entry_id => $checklist_date) {
					$open_absences .= "» $checklist_date: " . $base_link . "&entry_id=$entry_id \n";
				}
				$open_absences .= "\n";
			}

			if (!$open_absences) {
				continue;
			}

			$placeholders = array( 'user' => $ilObjUser, 'open_absences' => $open_absences );

			$notification = srNotification::getInstanceByName('absence_reminder');
			$sent = $notification->send($sender, $placeholders);

			if ($sent) {
				$last_reminder->setLastReminder(date('Y-m-d'));
				$last_reminder->update();
			}
		}
	}


	/**
	 *
	 */
	public function logout() {
		global $DIC;
		$ilAuth = $DIC["ilAuthSession"];
		$ilAuth->logout();
	}
}

class ilSessionMock {

	public function get($what, $default) {
		return $default;
	}
}