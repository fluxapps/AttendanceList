<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once 'Services/ActiveRecord/class.ActiveRecord.php';
require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/classes/class.ilAttendanceListPlugin.php';
/**
 * Class xaliChecklist
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xaliChecklist extends ActiveRecord {

	const DB_TABLE_NAME = "xali_checklist";


	/**
	 * @return string
	 */
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
	protected $obj_id;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_is_unique        true
	 * @db_fieldtype        date
	 */
	protected $checklist_date;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 */
	protected $last_edited_by;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 */
	protected $last_update;


	/**
	 *
	 */
	public function create() {
		parent::create();

	}


	/**
	 * @param $user_id
	 *
	 * @return xaliChecklistEntry
	 */
	public function getEntryOfUser($user_id) {
		$where = xaliChecklistEntry::where(array('checklist_id' => $this->id, 'user_id' => $user_id));
		if ($where->hasSets()) {
			return $where->first();
		}

		$entry = new xaliChecklistEntry();
		$entry->setChecklistId($this->id);
		$entry->setUserId($user_id);
		return $entry;
	}


	/**
	 * @return int
	 */
	public function getEntriesCount() {
		return xaliChecklistEntry::where(array('checklist_id' => $this->getId()))->count();
	}


	/**
	 * @return bool
	 */
	public function isComplete() {
		return $this->getEntriesCount() == count(ilAttendanceListPlugin::getInstance()->getMembers(ilAttendanceListPlugin::lookupRefId($this->obj_id)));
	}


	/**
	 * @param $status
	 *
	 * @return int
	 */
	public function getStatusCount($status) {
		return xaliChecklistEntry::where(array('status' => $status, 'checklist_id' => $this->getId()))->count();
	}


	/**
	 * @return bool
	 */
	public function isEmpty() {
		return $this->last_edited_by == null;
	}


	/**
	 *
	 */
	public function delete() {
		foreach (xaliChecklistEntry::where(array('checklist_id' => $this->id))->get() as $entry) {
			$entry->delete();
		}
		parent::delete();
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
	public function getObjId() {
		return $this->obj_id;
	}


	/**
	 * @param int $obj_id
	 */
	public function setObjId($obj_id) {
		$this->obj_id = $obj_id;
	}


	/**
	 * @return int
	 */
	public function getChecklistDate() {
		return $this->checklist_date;
	}


	/**
	 * @param int $checklist_date
	 */
	public function setChecklistDate($checklist_date) {
		$this->checklist_date = $checklist_date;
	}


	/**
	 * @return int
	 */
	public function getLastEditedBy() {
		return $this->last_edited_by;
	}


	/**
	 * @param int $last_edited_by
	 */
	public function setLastEditedBy($last_edited_by) {
		$this->last_edited_by = $last_edited_by;
	}


	/**
	 * @return int
	 */
	public function getLastUpdate() {
		return $this->last_update;
	}


	/**
	 * @param int $last_update
	 */
	public function setLastUpdate($last_update) {
		$this->last_update = $last_update;
	}

}