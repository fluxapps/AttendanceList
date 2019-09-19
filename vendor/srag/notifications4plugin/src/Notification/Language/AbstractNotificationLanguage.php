<?php

namespace srag\Notifications4Plugin\AttendanceList\Notification\Language;

use ActiveRecord;
use arConnector;
use ilDateTime;
use srag\DIC\AttendanceList\DICTrait;
use srag\Notifications4Plugin\AttendanceList\Notification\Language\Repository as NotificationLanguageRepository;
use srag\Notifications4Plugin\AttendanceList\Notification\Language\RepositoryInterface as NotificationLanguageRepositoryInterface;
use srag\Notifications4Plugin\AttendanceList\Notification\Repository as NotificationRepository;
use srag\Notifications4Plugin\AttendanceList\Notification\RepositoryInterface as NotificationRepositoryInterface;
use srag\Notifications4Plugin\AttendanceList\Utils\Notifications4PluginTrait;

/**
 * Class AbstractNotificationLanguage
 *
 * @package srag\Notifications4Plugin\AttendanceList\Notification\Language
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
abstract class AbstractNotificationLanguage extends ActiveRecord implements NotificationLanguage {

	use DICTrait;
	use Notifications4PluginTrait;


	/**
	 * @inheritdoc
	 */
	protected static function notification($notification_class) {
		return NotificationRepository::getInstance($notification_class, static::class);
	}


	/**
	 * @inheritdoc
	 */
	protected static function notificationLanguage() {
		return NotificationLanguageRepository::getInstance(static::class);
	}


	/**
	 * @return string
	 */
	public function getConnectorContainerName() {
		return static::TABLE_NAME;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public static function returnDbTableName() {
		return static::TABLE_NAME;
	}


	/**
	 *
	 */
	public static function updateDB_()/*: void*/ {
		self::updateDB();

		if (self::dic()->database()->sequenceExists(static::TABLE_NAME)) {
			self::dic()->database()->dropSequence(static::TABLE_NAME);
		}

		self::dic()->database()->createAutoIncrement(static::TABLE_NAME, "id");
	}


	/**
	 *
	 */
	public static function dropDB_()/*: void*/ {
		self::dic()->database()->dropTable(static::TABLE_NAME, false);

		self::dic()->database()->dropAutoIncrementTable(static::TABLE_NAME);
	}


	/**
	 * @var int
	 *
	 * @con_has_field    true
	 * @con_fieldtype    integer
	 * @con_length       8
	 * @con_is_notnull   true
	 * @con_is_primary   true
	 */
	protected $id = 0;
	/**
	 * @var int
	 *
	 * @con_has_field    true
	 * @con_fieldtype    integer
	 * @con_length       8
	 * @con_is_notnull   true
	 */
	protected $notification_id;
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_length       2
	 * @con_is_notnull   true
	 */
	protected $language = "";
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    clob
	 * @con_length       256
	 * @con_is_notnull   true
	 */
	protected $subject = "";
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    clob
	 * @con_length       4000
	 * @con_is_notnull   true
	 */
	protected $text = "";
	/**
	 * @var ilDateTime
	 *
	 * @con_has_field    true
	 * @con_fieldtype    timestamp
	 * @con_is_notnull   true
	 */
	protected $created_at;
	/**
	 * @var ilDateTime
	 *
	 * @con_has_field    true
	 * @con_fieldtype    timestamp
	 * @con_is_notnull   true
	 */
	protected $updated_at;


	/**
	 * AbstractNotificationLanguage constructor
	 *
	 * @param int              $primary_key_value
	 * @param arConnector|null $connector
	 */
	public function __construct(/*int*/ $primary_key_value = 0, /*?*/ arConnector $connector = null) {
		//parent::__construct($primary_key_value, $connector);
	}


	/**
	 * @inheritdoc
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * @inheritdoc
	 */
	public function setId($id)/*: void*/ {
		$this->id = $id;
	}


	/**
	 * @inheritdoc
	 */
	public function getNotificationId() {
		return $this->notification_id;
	}


	/**
	 * @inheritdoc
	 */
	public function setNotificationId($notification_id)/*: void*/ {
		$this->notification_id = $notification_id;
	}


	/**
	 * @inheritdoc
	 */
	public function getLanguage() {
		return $this->language;
	}


	/**
	 * @inheritdoc
	 */
	public function setLanguage($language)/*: void*/ {
		$this->language = $language;
	}


	/**
	 * @inheritdoc
	 */
	public function getSubject() {
		return $this->subject;
	}


	/**
	 * @inheritdoc
	 */
	public function setSubject($subject)/*: void*/ {
		$this->subject = $subject;
	}


	/**
	 * @inheritdoc
	 */
	public function getText() {
		return $this->text;
	}


	/**
	 * @inheritdoc
	 */
	public function setText($text)/*: void*/ {
		$this->text = $text;
	}


	/**
	 * @inheritdoc
	 */
	public function getCreatedAt() {
		return $this->created_at;
	}


	/**
	 * @inheritdoc
	 */
	public function setCreatedAt(ilDateTime $created_at)/*: void*/ {
		$this->created_at = $created_at;
	}


	/**
	 * @inheritdoc
	 */
	public function getUpdatedAt() {
		return $this->updated_at;
	}


	/**
	 * @inheritdoc
	 */
	public function setUpdatedAt(ilDateTime $updated_at)/*: void*/ {
		$this->updated_at = $updated_at;
	}
}
