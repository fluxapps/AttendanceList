<?php
class xaliAbsenceStatement extends ActiveRecord {

	const TABLE_NAME = 'xali_absence_statement';

	static function returnDbTableName(): string
    {
		return self::TABLE_NAME;
	}

	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 * @db_is_primary       true
	 */
	protected ?string $entry_id;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 */
	protected int $reason_id = 0;
	/**
	 * @var String
	 *
	 * @db_has_field        true
	 * @db_is_unique        true
	 * @db_length           256
	 * @db_fieldtype        text
	 */
	protected string $comment_text = "";
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 */
	protected int $file_id = 0;


	/**
	 * @return null
	 */
	public function getReason() {
		if ($this->getReasonId()) {
		    $reason = xaliAbsenceReason::find($this->getReasonId());
		    if ($reason) {
                return $reason->getTitle();
            }
		}

		return NULL;
	}

	public function getEntryId(): string
    {
		return $this->entry_id;
	}

	public function setEntryId(string $entry_id): void
    {
		$this->entry_id = $entry_id;
	}

	public function getReasonId(): int
    {
		return $this->reason_id;
	}

	public function setReasonId(int $reason_id): void
    {
		$this->reason_id = $reason_id;
	}

	public function getComment(): string
    {
		return $this->comment_text;
	}

	public function setComment(string $comment): void
    {
		$this->comment_text = $comment;
	}

	public function getFileId(): int
    {
		return $this->file_id;
	}

	public function setFileId(int $file_id): void
    {
		$this->file_id = $file_id;
	}
}