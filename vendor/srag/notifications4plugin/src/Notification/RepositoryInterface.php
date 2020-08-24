<?php

namespace srag\Notifications4Plugin\AttendanceList\Notification;

use srag\DataTableUI\AttendanceList\Component\Settings\Settings;

/**
 * Interface RepositoryInterface
 *
 * @package srag\Notifications4Plugin\AttendanceList\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface RepositoryInterface
{

    /**
     * @param NotificationInterface $notification
     */
    public function deleteNotification(NotificationInterface $notification);


    /**
     * @internal
     */
    public function dropTables();


    /**
     * @param NotificationInterface $notification
     *
     * @return NotificationInterface
     */
    public function duplicateNotification(NotificationInterface $notification);


    /**
     * @return FactoryInterface
     */
    public function factory();


    /**
     * @param int $id
     *
     * @return NotificationInterface|null
     */
    public function getNotificationById($id);


    /**
     * @param string $name
     *
     * @return NotificationInterface|null
     */
    public function getNotificationByName($name);


    /**
     * @param Settings|null $settings
     *
     * @return NotificationInterface[]
     */
    public function getNotifications(?Settings $settings = null);


    /**
     * @return int
     */
    public function getNotificationsCount();


    /**
     * @internal
     */
    public function installTables();


    /**
     * @param string $name |null
     *
     * @return NotificationInterface|null
     *
     * @deprecated
     */
    public function migrateFromOldGlobalPlugin($name = null);


    /**
     * @param NotificationInterface $notification
     */
    public function storeNotification(NotificationInterface $notification);
}
