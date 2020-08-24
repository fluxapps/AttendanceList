<?php

namespace srag\Notifications4Plugin\AttendanceList\Notification;

use srag\Notifications4Plugin\AttendanceList\Notification\Form\FormBuilder;
use srag\Notifications4Plugin\AttendanceList\Notification\Table\TableBuilder;
use stdClass;

/**
 * Interface FactoryInterface
 *
 * @package srag\Notifications4Plugin\AttendanceList\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface FactoryInterface
{

    /**
     * @param stdClass $data
     *
     * @return NotificationInterface
     */
    public function fromDB(stdClass $data);


    /**
     * @param NotificationCtrl      $parent
     * @param NotificationInterface $notification
     *
     * @return FormBuilder
     */
    public function newFormBuilderInstance(NotificationCtrl $parent, NotificationInterface $notification);


    /**
     * @return NotificationInterface
     */
    public function newInstance();


    /**
     * @param NotificationsCtrl $parent
     *
     * @return TableBuilder
     */
    public function newTableBuilderInstance(NotificationsCtrl $parent);
}
