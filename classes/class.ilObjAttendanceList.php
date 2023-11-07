<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Class ilObjAttendanceList
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ilObjAttendanceList extends ilObjectPlugin implements ilLPStatusPluginInterface {

	protected function initType(): void
    {
		$this->setType(ilAttendanceListPlugin::PLUGIN_ID);
	}


	protected function doDelete(): void
    {
		foreach (xaliChecklist::where(array( 'obj_id' => $this->id ))->get() as $checklist) {
			$checklist->delete();
		}
		$xaliSetting = xaliSetting::findOrGetInstance($this->id);
		if ($xaliSetting->getId()) {
			$xaliSetting->delete();
		}
	}


	public function getOfflineStatus(): bool
    {
		return !xaliSetting::find($this->getId())->getIsOnline();
	}


	/**
	 * @param $user_id
	 *
	 * @return mixed
	 */
	public function getOpenAbsenceStatementsForUser($user_id): mixed
    {
		global $DIC;
		$ilDB = $DIC['ilDB'];
		$sql = "SELECT 
				    " . xaliChecklistEntry::DB_TABLE_NAME . " . *, " . xaliChecklist::DB_TABLE_NAME . ".checklist_date
				FROM
				    " . xaliChecklist::DB_TABLE_NAME . "
				        INNER JOIN
				    " . xaliChecklistEntry::DB_TABLE_NAME . " ON " . xaliChecklistEntry::DB_TABLE_NAME . ".checklist_id = "
			. xaliChecklist::DB_TABLE_NAME . ".id
				        LEFT JOIN
				    " . xaliAbsenceStatement::TABLE_NAME . " ON " . xaliAbsenceStatement::TABLE_NAME . ".entry_id = "
			. xaliChecklistEntry::DB_TABLE_NAME . ".id
				WHERE
				    " . xaliChecklist::DB_TABLE_NAME . ".obj_id = {$this->getId()} AND user_id = $user_id
				        AND " . xaliChecklistEntry::DB_TABLE_NAME . ".status = 1
				        AND " . xaliAbsenceStatement::TABLE_NAME . ".entry_id IS NULL";
		$set = $ilDB->query($sql);

		return $set->numRows();
	}


	/**
	 * Get all user ids with LP status completed
	 *
	 * @return array
	 */
	public function getLPCompleted(): array
    {
		return xaliUserStatus::where(array(
			'status' => ilLPStatus::LP_STATUS_COMPLETED_NUM,
			'attendancelist_id' => $this->getId()
		))->getArray(NULL, 'user_id');
	}


	/**
	 * Get all user ids with LP status not attempted
	 *
	 * @return array
	 */
	public function getLPNotAttempted(): array
    {
		$operators = array(
			'status' => '!=',
			'attendancelist_id' => '='
		);
		$other_than_not_attempted = xaliUserStatus::where(array(
			'status' => ilLPStatus::LP_STATUS_NOT_ATTEMPTED_NUM,
			'attendancelist_id' => $this->getId()
		), $operators)->getArray(NULL, 'user_id');

		return array_diff($this->plugin->getMembers($this->plugin->lookupRefId($this->getId())), $other_than_not_attempted);
	}


	/**
	 * Get all user ids with LP status failed
	 *
	 * @return array
	 */
	public function getLPFailed(): array
    {
		return xaliUserStatus::where(array(
			'status' => ilLPStatus::LP_STATUS_FAILED_NUM,
			'attendancelist_id' => $this->getId()
		))->getArray(NULL, 'user_id');
	}


	/**
	 * Get all user ids with LP status in progress
	 *
	 * @return array
	 */
	public function getLPInProgress(): array
    {
		return xaliUserStatus::where(array(
			'status' => ilLPStatus::LP_STATUS_IN_PROGRESS_NUM,
			'attendancelist_id' => $this->getId()
		))->getArray(NULL, 'user_id');
	}


	/**
	 * Get current status for given user
	 *
	 * @param int $a_user_id
	 *
	 * @return int
	 */
	public function getLPStatusForUser($a_user_id): int
    {
		$user_status = xaliUserStatus::where(array(
			'user_id' => $a_user_id,
			'attendancelist_id' => $this->getId()
		))->first();
		if ($user_status) {
			return $user_status->getStatus();
		}

		return ilLPStatus::LP_STATUS_NOT_ATTEMPTED_NUM;
	}


    /**
     * @inheritDoc
     *
     * @param ilObjAttendanceList $new_obj
     */
    protected function doCloneObject(/*ilObjAttendanceList*/ $new_obj, /*int*/ $a_target_id, /*?int*/ $a_copy_id = null): void
    {
        $xaliSetting = xaliSetting::findOrGetInstance($this->id);
        $xaliSettingClone = $xaliSetting->copy();
        $xaliSettingClone->setId($new_obj->id);
        $xaliSettingClone->store();

        foreach (xaliChecklist::where(array( 'obj_id' => $this->id ))->get() as $checklist) {
            $checklistClone = $checklist->copy();
            $checklistClone->setObjId($new_obj->id);
            $checklistClone->store();
        }
    }
}