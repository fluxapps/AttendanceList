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
	const F_ABSENCE_REASONS_COMMENT = 'comment_req';
	const F_ABSENCE_REASONS_UPLOAD = 'upload_req';


	/**
	 * @return string
	 */
	static function returnDbTableName() {
		return 'xali_absence_reasons';
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
	 * @var String
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $title;
	/**
	 * @var String
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           256
	 */
	protected $info;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           1
	 */
	protected $comment_req;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           1
	 */
	protected $upload_req;


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
	 * @return String
	 */
	public function getTitle() {
		return $this->title;
	}


	/**
	 * @param String $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}


	/**
	 * @return String
	 */
	public function getInfo() {
		return $this->info;
	}


	/**
	 * @param String $info
	 */
	public function setInfo($info) {
		$this->info = $info;
	}


	/**
	 * @return int
	 */
	public function getCommentReq() {
		return $this->comment_req;
	}


	/**
	 * @param int $comment_req
	 */
	public function setCommentReq($comment_req) {
		$this->comment_req = $comment_req;
	}


	/**
	 * @return int
	 */
	public function getUploadReq() {
		return $this->upload_req;
	}


	/**
	 * @param int $upload_req
	 */
	public function setUploadReq($upload_req) {
		$this->upload_req = $upload_req;
	}


}