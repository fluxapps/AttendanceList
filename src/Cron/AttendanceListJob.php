<?php

namespace srag\Plugins\AttendanceList\Cron;

use ilAttendanceListPlugin;
use ilCronJob;
use ilCronJobResult;
use srag\DIC\AttendanceList\DICTrait;
use xaliCron;

/**
 * Class AttendanceListJob
 *
 * @package srag\Plugins\AttendanceList\Cron
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class AttendanceListJob extends ilCronJob
{

    use DICTrait;

    const CRON_JOB_ID = ilAttendanceListPlugin::PLUGIN_ID;
    const PLUGIN_CLASS_NAME = ilAttendanceListPlugin::class;


    /**
     * AttendanceListJob constructor
     */
    public function __construct()
    {

    }


    /**
     * @inheritDoc
     */
    public function getId() : string
    {
        return self::CRON_JOB_ID;
    }


    /**
     * @inheritDoc
     */
    public function hasAutoActivation() : bool
    {
        return true;
    }


    /**
     * @inheritDoc
     */
    public function hasFlexibleSchedule() : bool
    {
        return true;
    }


    /**
     * @inheritDoc
     */
    public function getDefaultScheduleType() : int
    {
        return self::SCHEDULE_TYPE_IN_MINUTES;
    }


    /**
     * @inheritDoc
     */
    public function getDefaultScheduleValue()/* : ?int*/
    {
        return 1;
    }


    /**
     * @inheritDoc
     */
    public function getTitle() : string
    {
        return ilAttendanceListPlugin::PLUGIN_NAME . ": " . self::plugin()->translate("cron_title");
    }


    /**
     * @inheritDoc
     */
    public function getDescription() : string
    {
        return self::plugin()->translate("cron_description");
    }


    /**
     * @inheritDoc
     */
    public function run() : ilCronJobResult
    {
        $result = new ilCronJobResult();

        $cron = new xaliCron();
        $cron->run();

        $result->setStatus(ilCronJobResult::STATUS_OK);

        return $result;
    }
}
