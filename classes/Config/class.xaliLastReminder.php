<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class xaliLastReminder
 *
 * database table to track the date of the last email reminder sent by this plugin
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xaliLastReminder extends ActiveRecord {

	const TABLE_NAME = 'xali_last_reminder';


	static function returnDbTableName() {
		return self::TABLE_NAME;
	}


	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 * @db_is_primary       true
	 */
	protected $user_id;
	/**
	 * @var String
	 *
	 * @db_has_field        true
	 * @db_fieldtype        date
	 */
	protected $last_reminder;


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
	 * @return String
	 */
	public function getLastReminder() {
		return $this->last_reminder;
	}


	/**
	 * @param String $last_reminder
	 */
	public function setLastReminder($last_reminder) {
		$this->last_reminder = $last_reminder;
	}
}