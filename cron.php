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
		$send_mail = array();
		foreach (xaliChecklistEntry::innerjoin(xaliAbsenceStatement::returnDbTableName(), 'id', 'entry_id')
			         ->where(array('status' => xaliChecklistEntry::STATUS_ABSENT_UNEXCUSED))->getArray() as $item) {
			$this->log->write(print_r($item, true));
		}
	}


}


class ilSessionMock {
	public function get($what, $default) {
		return $default;
	}

}