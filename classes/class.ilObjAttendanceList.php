<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once ('./Services/Repository/classes/class.ilObjectPlugin.php');
require_once ('./Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/classes/Checklist/class.xaliChecklist.php');
/**
 * Class ilObjAttendanceList
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ilObjAttendanceList extends ilObjectPlugin {

	protected function initType() {
		$this->setType("xali");
	}


	protected function doDelete() {
		foreach (xaliChecklist::where(array('obj_id' => $this->id))->get() as $checklist) {
			$checklist->delete();
		}
	}
}