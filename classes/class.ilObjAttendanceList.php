<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once ('./Services/Repository/classes/class.ilObjectPlugin.php');
require_once ('./Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/classes/Checklist/class.xaliChecklist.php');
require_once ('./Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/classes/UserStatus/class.xaliUserStatus.php');
require_once ('./Services/Tracking/interfaces/interface.ilLPStatusPlugin.php');
/**
 * Class ilObjAttendanceList
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ilObjAttendanceList extends ilObjectPlugin implements ilLPStatusPluginInterface {

	protected function initType() {
		$this->setType("xali");
	}


	protected function doDelete() {
		foreach (xaliChecklist::where(array('obj_id' => $this->id))->get() as $checklist) {
			$checklist->delete();
		}
		$xaliSetting = xaliSetting::findOrGetInstance($this->id);
		if ($xaliSetting->getId()) {
			$xaliSetting->delete();
		}
	}

	public function getOfflineStatus() {
		return !xaliSetting::find($this->getId())->getIsOnline();
	}

	/**
	 * Get all user ids with LP status completed
	 *
	 * @return array
	 */
	public function getLPCompleted()
	{
		return xaliUserStatus::where(array(
			'status' => ilLPStatus::LP_STATUS_COMPLETED_NUM,
			'attendancelist_id' => $this->getId()
		))->getArray(null, 'user_id');
	}

	/**
	 * Get all user ids with LP status not attempted
	 *
	 * @return array
	 */
	public function getLPNotAttempted()
	{
		$operators = array(
			'status' => '!=',
			'attendancelist_id' => '='
		);
		$other_than_not_attempted = xaliUserStatus::where(array(
			'status' => ilLPStatus::LP_STATUS_NOT_ATTEMPTED_NUM,
			'attendancelist_id' => $this->getId()
		), $operators)->getArray(null, 'user_id');

		return array_diff($this->plugin->getMembers($this->plugin->lookupRefId($this->getId())), $other_than_not_attempted);
	}
	/**
	 * Get all user ids with LP status failed
	 *
	 * @return array
	 */
	public function getLPFailed()
	{
		return xaliUserStatus::where(array(
			'status' => ilLPStatus::LP_STATUS_FAILED_NUM,
			'attendancelist_id' => $this->getId()
		))->getArray(null, 'user_id');
	}
	/**
	 * Get all user ids with LP status in progress
	 *
	 * @return array
	 */
	public function getLPInProgress()
	{
		return xaliUserStatus::where(array(
			'status' => ilLPStatus::LP_STATUS_IN_PROGRESS_NUM,
			'attendancelist_id' => $this->getId()
		))->getArray(null, 'user_id');
	}
	/**
	 * Get current status for given user
	 *
	 * @param int $a_user_id
	 * @return int
	 */
	public function getLPStatusForUser($a_user_id)
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

}