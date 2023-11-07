<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class xaliSetting
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xaliSetting extends ActiveRecord {

	const DB_TABLE_NAME = "xali_data";
	const CALC_AUTO_MINIMUM_ATTENDANCE = -1;


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
	 */
	protected ?string $id;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           1
	 */
	protected int $is_online = 0;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           8
	 */
	protected int $minimum_attendance = 80;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        integer
	 * @db_length           1
	 */
	protected int $activation = 0;
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        date
	 */
	protected string $activation_from = "";
	/**
	 * @var int
	 *
	 * @db_has_field        true
	 * @db_fieldtype        date
	 */
	protected string $activation_to = "";
	/**
	 * @var array
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           128
	 */
	protected array $activation_weekdays = [];


	/**
	 * @return string
	 */
	public function getId(): string
    {
		return $this->id;
	}

	public function setId(string $id): void
    {
		$this->id = $id;
	}

	public function getIsOnline(): int
    {
		return $this->is_online;
	}

	public function setIsOnline(int $is_online): void
    {
		$this->is_online = $is_online;
	}

	public function getMinimumAttendance(): int
    {
		return $this->minimum_attendance;
	}

	public function setMinimumAttendance(int $minimum_attendance): void
    {
		$this->minimum_attendance = $minimum_attendance;
	}


	public function getActivation(): int
    {
		return $this->activation;
	}

	public function setActivation(int $activation): void
    {
		$this->activation = $activation;
	}

	public function getActivationFrom(): string
    {
		return $this->activation_from;
	}


	public function setActivationFrom(string $activation_from): void
    {
		$this->activation_from = $activation_from;
	}

	public function getActivationTo(): string
    {
		return $this->activation_to;
	}

	public function setActivationTo(string $activation_to): void
    {
		$this->activation_to = $activation_to;
	}

	public function getActivationWeekdays(): array
    {
		return $this->activation_weekdays;
	}

	public function setActivationWeekdays(array $activation_weekdays): void
    {
		$this->activation_weekdays = $activation_weekdays;
	}


	public function sleep($field_name): string|bool|null
    {
		if ($field_name == 'activation_weekdays') {
			return json_encode($this->getActivationWeekdays());
		}

		return parent::sleep($field_name);
	}

	public function wakeUp($field_name, $field_value): mixed
    {
		if ($field_name == 'activation_weekdays' && $field_value) {
			return json_decode($field_value, true);
		}

		return parent::wakeUp($field_name, $field_value);
	}

    /**
     * @throws Exception
     */
    public function createOrDeleteEmptyLists($create, $delete): void
    {
        global $DIC;

        $currentUser = $DIC->user();

		$begin = $this->getActivationFrom();
		$end = $this->getActivationTo();
		$weekdays = $this->getActivationWeekdays();
		if (!$weekdays || empty($weekdays) || !$begin || !$end) {
			return;
		}

		// delete empty lists outside defined dates
		if ($delete) {
			foreach (xaliChecklist::where(array( 'obj_id' => $this->getId(), 'last_edited_by' => NULL ))->get() as $checklist) {
				if ($checklist->getChecklistDate() < $begin
					|| $checklist->getChecklistDate() > $end
					|| !in_array(date("D", strtotime($checklist->getChecklistDate())), $weekdays)) {
					$checklist->delete();
				}
			}
		}

		// create empty lists inside defined dates
		if ($create) {
			$begin = new DateTime($begin);
			$end = new DateTime($end);
			$end->setTime(0, 0, 1); // if the time is 00:00:00, the last day will not be included by DatePeriod

			$interval = DateInterval::createFromDateString('1 day');
			$period = new DatePeriod($begin, $interval, $end);

			foreach ($period as $dt) {
				if (in_array($dt->format("D"), $weekdays)) {
					$where = xaliChecklist::where(array( 'checklist_date' => $dt->format('Y-m-d'), 'obj_id' => $this->getId() ));
					if (!$where->hasSets()) {
						$checklist = new xaliChecklist();
						$checklist->setChecklistDate($dt->format('Y-m-d'));
						$checklist->setObjId($this->getId());
                        $checklist->setLastEditedBy($currentUser->getId());
                        $checklist->setLastUpdate(time());
						$checklist->create();
					}
				}
			}
		}

		// update LP
		xaliUserStatus::updateUserStatuses($this->id);
	}
}