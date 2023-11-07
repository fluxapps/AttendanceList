<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class xaliAbsenceReason
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xaliAbsenceReason extends ActiveRecord {

	const F_ABSENCE_REASONS_TITLE = 'title';
	const F_ABSENCE_REASONS_INFO = 'info';
	const F_ABSENCE_REASONS_HAS_COMMENT = 'has_comment';
	const F_ABSENCE_REASONS_COMMENT_REQ = 'comment_req';
	const F_ABSENCE_REASONS_HAS_UPLOAD = 'has_upload';
	const F_ABSENCE_REASONS_UPLOAD_REQ = 'upload_req';
	const TABLE_NAME = 'xali_absence_reasons';


	/**
	 * @return string
	 */
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
	 * @con_sequence        true
	 */
	protected ?string $id;
	/**
	 * @var String
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected string $title;
	/**
	 * @var String
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected string $info;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           1
	 */
	protected int $has_comment;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           1
	 */
	protected int $comment_req;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           1
	 */
	protected int $has_upload;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           1
	 */
	protected int $upload_req;

	public function getId(): string
    {
		return $this->id;
	}

	public function setId(string $id): void
    {
		$this->id = $id;
	}

	public function getTitle(): string
    {
		return $this->title;
	}

	public function setTitle(string $title): void
    {
		$this->title = $title;
	}

	public function getInfo(): string
    {
		return $this->info;
	}

	public function setInfo(string $info): void
    {
		$this->info = $info;
	}

	public function hasComment(): int
    {
		return $this->has_comment;
	}

	public function setHasComment($has_comment): void
    {
		$this->has_comment = $has_comment;
	}

	public function hasUpload(): bool
    {
		return $this->has_upload;
	}

	public function setHasUpload(bool $has_upload): void
    {
		$this->has_upload = $has_upload;
	}

	public function getCommentReq(): bool
    {
		return $this->comment_req;
	}

	public function setCommentReq(bool $comment_req): void
    {
		$this->comment_req = $comment_req;
	}

	public function getUploadReq(): bool
    {
		return $this->upload_req;
	}

	public function setUploadReq(bool $upload_req): void
    {
		$this->upload_req = $upload_req;
	}
}