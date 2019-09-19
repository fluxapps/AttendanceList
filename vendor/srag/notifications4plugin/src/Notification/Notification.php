<?php

namespace srag\Notifications4Plugin\AttendanceList\Notification;

use ilDateTime;
use srag\Notifications4Plugin\AttendanceList\Notification\Language\NotificationLanguage;

/**
 * Interface Notification
 *
 * @package srag\Notifications4Plugin\AttendanceList\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Notification {

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
	 * @return string
	 */
	public function getName();


	/**
	 * @param string $name
	 */
	public function setName($name)/*: void*/ ;


	/**
	 * @return string
	 */
	public function getTitle();


	/**
	 * @param string $title
	 */
	public function setTitle($title)/*: void*/ ;


	/**
	 * @return string
	 */
	public function getDescription();


	/**
	 * @param string $description
	 */
	public function setDescription($description)/*: void*/ ;


	/**
	 * @return string
	 */
	public function getDefaultLanguage();


	/**
	 * @param string $default_language
	 */
	public function setDefaultLanguage($default_language)/*: void*/ ;


	/**
	 * @return string
	 */
	public function getParser();


	/**
	 * @param string $parser
	 */
	public function setParser($parser)/*: void*/ ;


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


	/**
	 * @return NotificationLanguage[]
	 */
	public function getLanguages();


	/**
	 * @param NotificationLanguage[] $languages
	 */
	public function setLanguages(array $languages)/*: void*/ ;


	/**
	 * @param NotificationLanguage $language
	 */
	public function addLanguage(NotificationLanguage $language)/*: void*/ ;


	/**
	 * @param string $language
	 *
	 * @return string
	 */
	public function getSubject($language = "");


	/**
	 * @param string $subject
	 * @param string $language
	 */
	public function setSubject($subject, $language)/*: void*/ ;


	/**
	 * @param string $language
	 *
	 * @return string
	 */
	public function getText($language = "");


	/**
	 * @param string $text
	 * @param string $language
	 */
	public function setText($text, $language)/*: void*/ ;
}
