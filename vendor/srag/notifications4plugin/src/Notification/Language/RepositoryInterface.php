<?php

namespace srag\Notifications4Plugin\AttendanceList\Notification\Language;

/**
 * Interface RepositoryInterface
 *
 * @package srag\Notifications4Plugin\AttendanceList\Notification\Language
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface RepositoryInterface {

	/**
	 * @param NotificationLanguage $language
	 */
	public function deleteLanguage(NotificationLanguage $language)/*: void*/ ;


	/**
	 * @param NotificationLanguage $language
	 *
	 * @return NotificationLanguage
	 */
	public function duplicateLanguage(NotificationLanguage $language);


	/**
	 * @return FactoryInterface
	 */
	public function factory();


	/**
	 * @param int $id
	 *
	 * @return NotificationLanguage|null
	 */
	public function getLanguageById($id)/*: ?NotificationLanguage*/ ;


	/**
	 * @param int    $notification_id
	 * @param string $language
	 *
	 * @return NotificationLanguage
	 */
	public function getLanguageForNotification($notification_id, $language);


	/**
	 * @return NotificationLanguage[]
	 */
	public function getLanguages();


	/**
	 * @param int $notification_id
	 *
	 * @return NotificationLanguage[]
	 */
	public function getLanguagesForNotification($notification_id);


	/**
	 * @param NotificationLanguage $language
	 */
	public function storeInstance(NotificationLanguage $language)/*: void*/ ;
}
