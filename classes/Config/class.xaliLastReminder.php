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


	static function returnDbTableName(): string
    {
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
	protected ?int $user_id;
	/**
	 * @var String
	 *
	 * @db_has_field        true
	 * @db_fieldtype        date
	 */
	protected string $last_reminder;

	public function getUserId(): int
    {
		return $this->user_id;
	}

	public function setUserId(int $user_id): void
    {
		$this->user_id = $user_id;
	}
	public function getLastReminder(): string
    {
		return $this->last_reminder;
	}

	public function setLastReminder(string $last_reminder): void
    {
		$this->last_reminder = $last_reminder;
	}
}