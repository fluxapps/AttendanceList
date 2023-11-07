<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class xaliChecklist
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xaliChecklist extends ActiveRecord {

	const DB_TABLE_NAME = "xali_checklist";

	static function returnDbTableName(): string
    {
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
	protected ?string $id;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 */
	protected int $obj_id;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_is_unique        true
	 * @db_fieldtype        date
	 */
	protected string $checklist_date;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 */
	protected int $last_edited_by;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 */
	protected int $last_update;


	/**
	 * @param $user_id
	 *
	 * @return xaliChecklistEntry
	 */
	public function getEntryOfUser($user_id): xaliChecklistEntry
    {
		$where = xaliChecklistEntry::where(array( 'checklist_id' => $this->id, 'user_id' => $user_id ));
		if ($where->hasSets()) {
			return $where->first();
		}

		$entry = new xaliChecklistEntry();
		$entry->setChecklistId($this->id);
		$entry->setUserId($user_id);
        $entry->save();

		return $entry;
	}


	public function getEntriesCount(): int
    {
		$members = ilAttendanceListPlugin::getInstance()->getMembers(ilAttendanceListPlugin::lookupRefId($this->obj_id));
		if (empty($members)) {
			return 0;
		}
		$operators = array(
			'checklist_id' => '=',
			'user_id' => 'IN'
		);

		return xaliChecklistEntry::where(array(
			'checklist_id' => $this->getId(),
			'user_id' => $members
		), $operators)->count();
	}

	public function isComplete(): bool
    {
		return $this->getEntriesCount() >= count(ilAttendanceListPlugin::getInstance()
				->getMembers(ilAttendanceListPlugin::lookupRefId($this->obj_id)));
	}

	public function hasSavedEntries(): bool
    {
		return $this->getEntriesCount() != 0;
	}

	public function getStatusCount($status): int
    {
		$members = ilAttendanceListPlugin::getInstance()->getMembers();
		if (empty($members)) {
			return 0;
		}
		$operators = array(
			'status' => '=',
			'checklist_id' => '=',
			'user_id' => 'IN'
		);

		return xaliChecklistEntry::where(array(
			'status' => $status,
			'checklist_id' => $this->getId(),
			'user_id' => $members
		), $operators)->count();
	}

	public function isEmpty(): bool
    {
		return $this->last_edited_by == NULL;
	}

	public function delete(): void
    {
		foreach (xaliChecklistEntry::where(array( 'checklist_id' => $this->id ))->get() as $entry) {
			$entry->delete();
		}
		parent::delete();
	}

	public function getId(): string
    {
		return $this->id;
	}

	public function setId($id): void
    {
		$this->id = $id;
	}

	public function getObjId(): int
    {
		return $this->obj_id;
	}

	public function setObjId($obj_id): void
    {
		$this->obj_id = $obj_id;
	}

	public function getChecklistDate($formatted = true): string
    {
		return $formatted ? date('D, d.m.Y', strtotime($this->checklist_date)) : $this->checklist_date;
	}

	public function setChecklistDate(string $checklist_date): void
    {
		$this->checklist_date = $checklist_date;
	}

	public function getLastEditedBy($as_string): int|string
    {
		if (!$as_string) {
			return $this->last_edited_by;
		}

		if (!$this->last_edited_by) {        // automatically created
			return ilAttendanceListPlugin::getInstance()->txt('automatically_created');
		}

		$name = ilObjUser::_lookupName($this->last_edited_by);

		return $name['firstname'] . ' ' . $name['lastname'];
	}

	public function setLastEditedBy(int $last_edited_by): void
    {
		$this->last_edited_by = $last_edited_by;
	}

	public function getLastUpdate(): int
    {
		return $this->last_update;
	}

	public function setLastUpdate(int $last_update): void
    {
		$this->last_update = $last_update;
	}
}