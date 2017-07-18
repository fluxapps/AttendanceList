<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once 'Services/ActiveRecord/class.ActiveRecord.php';
/**
 * Class xaliChecklistEntry
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xaliChecklistEntry extends ActiveRecord {


	const DB_TABLE_NAME = "xali_entry";

	const STATUS_ABSENT_UNEXCUSED = 1;
	const STATUS_ABSENT_EXCUSED = 2; // DEPRECATED
	const STATUS_PRESENT = 3;

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
		$this->status = $status;
	}

}