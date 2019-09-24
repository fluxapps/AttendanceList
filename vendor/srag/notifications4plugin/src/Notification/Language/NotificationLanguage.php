<?php

namespace srag\Notifications4Plugin\AttendanceList\Notification\Language;

use ilDateTime;

/**
 * Interface NotificationLanguage
 *
 * @package srag\Notifications4Plugin\AttendanceList\Notification\Language
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface NotificationLanguage {

	/**
	 * @var string
	 *
	 * @abstract
	 */
	const TABLE_NAME = "";


	/**
	 * @return int
	 */
	public function getId();


	/**
	 * @param int $id
	 */
	public function setId($id)/*: void*/ ;


	/**
	 * @return int
	 */
	public function getNotificationId();


	/**
	 * @param int $notification_id
	 */
	public function setNotificationId($notification_id)/*: void*/ ;


	/**
	 * @return string
	 */
	public function getLanguage();


	/**
	 * @param string $language
	 */
	public function setLanguage($language)/*: void*/ ;


	/**
	 * @return string
	 */
	public function getSubject();


	/**
	 * @param string $subject
	 */
	public function setSubject($subject)/*: void*/ ;


	/**
	 * @return string
	 */
	public function getText();


	/**
	 * @param string $text
	 */
	public function setText($text)/*: void*/ ;


	/**
	 * @return ilDateTime
	 */
	public function getCreatedAt();


	/**
	 * @param ilDateTime $created_at
	 */
	public function setCreatedAt(ilDateTime $created_at)/*: void*/ ;


	/**
	 * @return ilDateTime
	 */
	public function getUpdatedAt();


	/**
	 * @param ilDateTime $updated_at
	 */
	public function setUpdatedAt(ilDateTime $updated_at)/*: void*/ ;
}
