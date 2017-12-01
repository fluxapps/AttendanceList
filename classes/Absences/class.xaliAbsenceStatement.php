<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
/**
 * Class xaliAbsenceStatement
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xaliAbsenceStatement extends ActiveRecord {

	/**
	 * @return string
	 */
	static function returnDbTableName() {
		return 'xali_absence_statement';
	}

	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 * @db_is_primary       true
	 */
	protected $entry_id;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 */
	protected $reason_id;
	/**
	 * @var String
	 *
	 * @db_has_field        true
	 * @db_is_unique        true
	 * @db_length           256
	 * @db_fieldtype        text
	 */
	protected $comment_text;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 */
	protected $file_id;


	/**
	 * @return null
	 */
	public function getReason() {
		if ($this->getReasonId()) {
			return xaliAbsenceReason::find($this->getReasonId())->getTitle();
		}
		return null;
	}


	/**
	 * @return string
	 */
	public function getEntryId() {
		return $this->entry_id;
	}


	/**
	 * @param string $entry_id
	 */
	public function setEntryId($entry_id) {
		$this->entry_id = $entry_id;
	}


	/**
	 * @return int
	 */
	public function getReasonId() {
		return $this->reason_id;
	}


	/**
	 * @param int $reason_id
	 */
	public function setReasonId($reason_id) {
		$this->reason_id = $reason_id;
	}


	/**
	 * @return String
	 */
	public function getComment() {
		return $this->comment_text;
	}


	/**
	 * @param String $comment
	 */
	public function setComment($comment) {
		$this->comment_text = $comment;
	}


	/**
	 * @return int
	 */
	public function getFileId() {
		return $this->file_id;
	}


	/**
	 * @param int $file_id
	 */
	public function setFileId($file_id) {
		$this->file_id = $file_id;
	}

}