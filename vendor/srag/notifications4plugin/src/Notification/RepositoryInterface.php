<?php

namespace srag\Notifications4Plugin\AttendanceList\Notification;

use srag\DIC\AttendanceList\Plugin\PluginInterface;

/**
 * Interface RepositoryInterface
 *
 * @package srag\Notifications4Plugin\AttendanceList\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface RepositoryInterface {

	/**
	 * @param Notification $notification
	 */
	public function deleteNotification(Notification $notification)/*: void*/ ;


	/**
	 * @param Notification    $notification
	 * @param PluginInterface $plugin
	 *
	 * @return Notification
	 */
	public function duplicateNotification(Notification $notification, PluginInterface $plugin);


	/**
	 * @return FactoryInterface
	 */
	public function factory();


	/**
	 * @param Notification[] $notifications
	 *
	 * @return array
	 */
	public function getArrayForSelection(array $notifications);


	/**
	 * @param int $id
	 *
	 * @return Notification|null
	 */
	public function getNotificationById($id)/*: ?Notification*/ ;


	/**
	 * @param string $name
	 *
	 * @return Notification|null
	 */
	public function getNotificationByName($name)/*: ?Notification*/ ;


	/**
	 * @param string|null $sort_by
	 * @param string|null $sort_by_direction
	 * @param int|null    $limit_start
	 * @param int|null    $limit_end
	 *
	 * @return Notification[]
	 */
	public function getNotifications($sort_by = null, $sort_by_direction = null, $limit_start = null, $limit_end = null);


	/**
	 * @return int
	 */
	public function getNotificationsCount();


	/**
	 * @param string $name |null
	 *
	 * @return Notification|null
	 *
	 * @deprecated
	 */
	public function migrateFromOldGlobalPlugin($name = null)/*: ?Notification*/ ;


	/**
	 * @param Notification $notification
	 */
	public function storeInstance(Notification $notification)/*: void*/ ;
}
