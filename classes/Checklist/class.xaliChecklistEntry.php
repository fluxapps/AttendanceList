<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */

use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;

/**
 * Class xaliChecklistEntry
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xaliChecklistEntry extends ActiveRecord {

	use Notifications4PluginsTrait;
	const DB_TABLE_NAME = "xali_entry";
	const STATUS_ABSENT_UNEXCUSED = 1;
	const STATUS_ABSENT_EXCUSED = 2; // DEPRECATED
	const STATUS_PRESENT = 3;
	const NOTIFICATION_NAME = "absence";


	static function returnDbTableName() {
		return self::DB_TABLE_NAME;
	}


	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 * @db_is_primary       true
	 * @con_sequence        true
	 */
	protected $id;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 */
	protected $checklist_id;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 */
	protected $user_id;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 */
	protected $status;
	protected $status_changed = false;


	/**
	 *
	 */
	public function create() {
		parent::create();
		if ($this->status == self::STATUS_ABSENT_UNEXCUSED) {
		    $this->sendAbsenceNotification();
		}
	}


	/**
	 *
	 */
	public function update() {
		if (($this->status == self::STATUS_ABSENT_UNEXCUSED) && $this->status_changed) {
			$this->sendAbsenceNotification();
		}
		parent::update();
	}


	/**
	 *
	 */
	protected function sendAbsenceNotification() {
		/** ilCtrl $ilCtrl */
		global $DIC;
		$ilCtrl = $DIC['ilCtrl'];
		$ilObjUser = new ilObjUser($this->getUserId());

		/** @var xaliChecklist $xaliChecklist */
		$xaliChecklist = xaliChecklist::find($this->getChecklistId());
		$ref_id = ilAttendanceListPlugin::lookupRefId($xaliChecklist->getObjId());
        $link = xaliConfig::getConfig(xaliConfig::F_HTTP_PATH) . '/goto.php?target=xali_' . $ref_id . '_' . $this->id;

		$parent_course = ilAttendanceListPlugin::getInstance()->getParentCourseOrGroup($ref_id);
		$absence_date = $xaliChecklist->getChecklistDate('d.m.Y');
		$absence = 'Kurs "' . $parent_course->getTitle() . "\": \n";
		$absence .= "» $absence_date: " . $link . "\n";

		$placeholders = array( 'user' => $ilObjUser, 'absence' => $absence );

		$notification = self::notification()->getNotificationByName(self::NOTIFICATION_NAME);

		$sender_id = xaliConfig::getConfig(xaliConfig::F_SENDER_REMINDER_EMAIL);
		$sender = self::sender()->factory()->internalMail($sender_id, $ilObjUser->getId());
		$sent = self::sender()->send($sender, $notification, $placeholders);

		if ($sent) {
			$interval = xaliConfig::getConfig(xaliConfig::F_INTERVAL_REMINDER_EMAIL);
			if (!$interval) {
				return true;
			}

			$last_reminder = xaliLastReminder::where(array( 'user_id' => $ilObjUser->getId() ))->first();

			if (!$last_reminder) {
				$last_reminder = new xaliLastReminder();
				$last_reminder->setLastReminder(date('Y-m-d'));
				$last_reminder->setUserId($ilObjUser->getId());
				$last_reminder->create();
			} elseif ($last_reminder->getLastReminder() <= date('Y-m-d', strtotime("now -$interval days"))) {
				$last_reminder->setLastReminder(date('Y-m-d'));
				$last_reminder->update();
			}
			// don't reset the last reminder if there has already been a reminder
		}
	}


	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * @param string $id
	 */
	public function setId($id) {
		$this->id = $id;
	}


	/**
	 * @return int
	 */
	public function getChecklistId() {
		return $this->checklist_id;
	}


	/**
	 * @param int $checklist_id
	 */
	public function setChecklistId($checklist_id) {
		$this->checklist_id = $checklist_id;
	}


	/**
	 * @return int
	 */
	public function getUserId() {
		return $this->user_id;
	}


	/**
	 * @param int $user_id
	 */
	public function setUserId($user_id) {
		$this->user_id = $user_id;
	}


	/**
	 * @return int
	 */
	public function getStatus() {
		return $this->status;
	}


	/**
	 * @param int $status
	 */
	public function setStatus($status) {
		if ($this->status != $status) {
			$this->status_changed = true;
		}
		$this->status = $status;
	}
}