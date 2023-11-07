<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once __DIR__ . '/../vendor/autoload.php';

use ILIAS\DI\Container;
use srag\CustomInputGUIs\AttendanceList\Loader\CustomInputGUIsLoaderDetector;
use srag\DIC\AttendanceList\DICTrait;
use srag\Notifications4Plugin\AttendanceList\Utils\Notifications4PluginTrait;

/**
 * Class ilAttendanceListPlugin
 *
 * @author            Theodor Truffer <tt@studer-raimann.ch>
 */
class ilAttendanceListPlugin extends ilRepositoryObjectPlugin {

	use DICTrait;
	use Notifications4PluginTrait;
	const PLUGIN_ID = 'xali';
	const PLUGIN_NAME = 'AttendanceList';
	const PLUGIN_CLASS_NAME = self::class;


    /**
     * @var bool
     */
    protected static $init_notifications = false;


    /**
     *
     */
    public static function initNotifications(): void
    {
        if (!self::$init_notifications) {
            self::$init_notifications = true;

            self::notifications4plugin()->withTableNamePrefix(self::PLUGIN_ID)->withPlugin(self::plugin());
        }
    }

	protected static ilAttendanceListPlugin $instance;
	protected ilDBInterface $db;


    public function __construct(
        ilDBInterface $db,
        ilComponentRepositoryWrite $component_repository,
        string $id
    ) {
        global $DIC;
        parent::__construct($db, $component_repository, $id);

		$this->db = $DIC->database();
	}


	/**
	 * @return ilAttendanceListPlugin
	 */
	public static function getInstance(): ilAttendanceListPlugin
    {
		if (!isset(self::$instance)) {
            global $DIC;

            /** @var $component_factory ilComponentFactory */
            $component_factory = $DIC['component.factory'];
            /** @var $plugin ilAttendanceListPlugin */
            $plugin  = $component_factory->getPlugin(ilAttendanceListPlugin::PLUGIN_ID);

			self::$instance = $plugin;
		}

		return self::$instance;
	}


    /**
     * @inheritDoc
     */
	protected function init(): void
    {
       self::initNotifications();
    }


    /**
	 * @return string
	 */
	function getPluginName(): string
    {
		return self::PLUGIN_NAME;
	}


	/**
	 * @inheritdoc
	 */
	public function updateLanguages($a_lang_keys = null): void
    {
		parent::updateLanguages($a_lang_keys);

        self::notifications4plugin()->installLanguages();
	}


	/**
	 *
	 */
	protected function uninstallCustom(): void
    {
		$this->db->dropTable(xaliConfig::TABLE_NAME, false);
		$this->db->dropTable(xaliLastReminder::TABLE_NAME, false);
		$this->db->dropTable(xaliAbsenceReason::TABLE_NAME, false);
		$this->db->dropTable(xaliAbsenceStatement::TABLE_NAME, false);
		$this->db->dropTable(xaliSetting::DB_TABLE_NAME, false);
		$this->db->dropTable(xaliChecklist::DB_TABLE_NAME, false);
		$this->db->dropTable(xaliChecklistEntry::DB_TABLE_NAME, false);
		$this->db->dropTable(xaliUserStatus::TABLE_NAME, false);
        self::notifications4plugin()->dropTables();
    }


	/**
	 * Get ref id for object id.
	 * The ref id is unambiguous since there can't be references to attendance lists.
	 *
	 * @param $obj_id
	 *
	 * @return int|null
     */
	static function lookupRefId($obj_id): ?int
    {
        $allReferences = ilObject2::_getAllReferences($obj_id);
        return array_shift($allReferences);
	}


	/**
	 * @return array
	 */
	public function getMembers($ref_id = 0): array
    {
		global $DIC;
		$rbacreview = $DIC['rbacreview'];
		static $members;
		if (!$members) {
			$ref_id = $ref_id ? $ref_id : $_GET['ref_id'];
			$parent = $this->getParentCourseOrGroup($ref_id);
			$member_role = $parent->getDefaultMemberRole();
			$members = $rbacreview->assignedUsers($member_role);
			$members = array_filter($members, function($usr_id) {
			    return ilObjUser::_exists($usr_id);
            });
		}

		return $members;
	}


	/**
	 * @return ilObjCourse|ilObjGroup
	 * @throws Exception
	 */
	public function getParentCourseOrGroup($ref_id = 0): ilObjGroup|ilObjCourse
    {
		$ref_id = $ref_id ? $ref_id : $_GET['ref_id'];
		$parent = ilObjectFactory::getInstanceByRefId($this->getParentCourseOrGroupId($ref_id));

		return $parent;
	}


	/**
	 * @param $ref_id
	 *
	 * @return int
	 * @throws Exception
	 */
	public function getParentCourseOrGroupId($ref_id): int
    {
		global $DIC;
		$tree = $DIC['tree'];
		$orig_ref_id = $ref_id;
		while (!in_array(ilObject2::_lookupType($ref_id, true), array( 'crs', 'grp' ))) {
			if ($ref_id == 1 || !$ref_id) {
				throw new Exception("Parent of ref id {$orig_ref_id} is neither course nor group.");
			}
			$ref_id = $tree->getParentId($ref_id);
		}

		return $ref_id;
	}


	/**
	 * @param $user_id
	 * @param $crs_ref_id
	 *
	 * @return array
	 */
	public function getAttendancesForUserAndCourse($user_id, $crs_ref_id): array
    {
		$obj_id = $this->getAttendanceListIdForCourse($crs_ref_id);
		$settings = new xaliSetting($obj_id);

		/** @var xaliUserStatus $xaliUserStatus */
		$xaliUserStatus = xaliUserStatus::getInstance($user_id, $obj_id);

		return array(
			'present' => $xaliUserStatus->getAttendanceStatuses(xaliChecklistEntry::STATUS_PRESENT),
			'absent' => $xaliUserStatus->getAttendanceStatuses(xaliChecklistEntry::STATUS_ABSENT_UNEXCUSED),
			'unedited' => $xaliUserStatus->getUnedited(),
			'percentage' => $xaliUserStatus->getReachedPercentage(),
			'minimum_attendance' => $obj_id ? $xaliUserStatus->calcMinimumAttendance() : 0
		);
	}


	/**
	 * @param      $crs_ref_id
	 * @param bool $get_ref_id
	 *
	 * @return int
	 */
	public function getAttendanceListIdForCourse($crs_ref_id, $get_ref_id = false): int
    {
		global $DIC;
		/** @var ilTree $tree */
		$tree = $DIC['tree'];
		$attendancelist = array_shift($tree->getSubTree($tree->getNodeData($crs_ref_id), true, $this->getId()));
		$ref_id = $attendancelist['child'];
		if ($get_ref_id) {
			return $ref_id;
		}

		return ilObjAttendanceList::_lookupObjectId($ref_id);
	}


    /**
     * @inheritDoc
     */
    public function exchangeUIRendererAfterInitialization(Container $dic) : Closure
    {
        return CustomInputGUIsLoaderDetector::exchangeUIRendererAfterInitialization();
    }


    /**
     * @inheritDoc
     */
    public function allowCopy() : bool
    {
        return true;
    }
}
