<?php

namespace srag\Notifications4Plugin\AttendanceList\Notification\Language;

use ilDateTime;
use ilDBConstants;
use srag\DIC\AttendanceList\DICTrait;
use srag\Notifications4Plugin\AttendanceList\Utils\Notifications4PluginTrait;

/**
 * Class Repository
 *
 * @package srag\Notifications4Plugin\AttendanceList\Notification\Language
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository implements RepositoryInterface {

	use DICTrait;
	use Notifications4PluginTrait;
	/**
	 * @var RepositoryInterface[]
	 */
	protected static $instances = [];


	/**
	 * @param string $language_class
	 *
	 * @return RepositoryInterface
	 */
	public static function getInstance($language_class) {
		if (!isset(self::$instances[$language_class])) {
			self::$instances[$language_class] = new self($language_class);
		}

		return self::$instances[$language_class];
	}


	/**
	 * @var string|NotificationLanguage
	 */
	protected $language_class;


	/**
	 * Repository constructor
	 *
	 * @param string $language_class
	 */
	private function __construct($language_class) {
		$this->language_class = $language_class;
	}


	/**
	 * @inheritdoc
	 */
	public function deleteLanguage(NotificationLanguage $language)/*: void*/ {
		self::dic()->database()->manipulateF('DELETE FROM ' . self::dic()->database()->quoteIdentifier($this->language_class::TABLE_NAME)
			. ' WHERE id=%s', [ ilDBConstants::T_INTEGER ], [ $language->getId() ]);
	}


	/**
	 * @inheritdoc
	 */
	public function duplicateLanguage(NotificationLanguage $language) {
		$duplicated_language = clone $language;

		$language->setId(0);

		return $duplicated_language;
	}


	/**
	 * @inheritdoc
	 */
	public function factory() {
		return Factory::getInstance($this->language_class);
	}


	/**
	 * @inheritdoc
	 */
	public function getLanguageById($id)/*: ?NotificationLanguage*/ {
		/**
		 * @var NotificationLanguage|null $language
		 */
		$language = self::dic()->database()->fetchObjectCallback(self::dic()->database()->queryF('SELECT * FROM ' . self::dic()->database()
				->quoteIdentifier($this->language_class::TABLE_NAME) . ' WHERE id=%s', [ ilDBConstants::T_INTEGER ], [ $id ]), [
			$this->factory(),
			"fromDB"
		]);

		return $language;
	}


	/**
	 * @inheritdoc
	 */
	public function getLanguageForNotification($notification_id, $language) {
		/**
		 * @var NotificationLanguage $l
		 */
		$l = self::dic()->database()->fetchObjectCallback(self::dic()->database()->queryF('SELECT * FROM ' . self::dic()->database()
				->quoteIdentifier($this->language_class::TABLE_NAME) . ' WHERE notification_id=%s AND language=%s', [
			ilDBConstants::T_INTEGER,
			ilDBConstants::T_TEXT
		], [ $notification_id, $language ]), [
			$this->factory(),
			"fromDB"
		]);

		if ($l === null) {
			$l = $this->factory()->newInstance();

			$l->setNotificationId($notification_id);

			$l->setLanguage($language);
		}

		return $l;
	}


	/**
	 * @inheritdoc
	 */
	public function getLanguages() {
		/**
		 * @var NotificationLanguage[] $languages
		 */
		$languages = self::dic()->database()->fetchAllCallback(self::dic()->database()->query('SELECT * FROM ' . self::dic()->database()
				->quoteIdentifier($this->language_class::TABLE_NAME)), [ $this->factory(), "fromDB" ]);

		return $languages;
	}


	/**
	 * @inheritdoc
	 */
	public function getLanguagesForNotification($notification_id) {
		/**
		 * @var NotificationLanguage[] $array
		 */
		$array = self::dic()->database()->fetchAllCallback(self::dic()->database()->queryF('SELECT * FROM ' . self::dic()->database()
				->quoteIdentifier($this->language_class::TABLE_NAME)
			. ' WHERE notification_id=%s', [ ilDBConstants::T_INTEGER ], [ $notification_id ]), [ $this->factory(), "fromDB" ]);

		$languages = [];

		foreach ($array as $language) {
			$languages[$language->getLanguage()] = $language;
		}

		return $languages;
	}


	/**
	 * @inheritdoc
	 */
	public function storeInstance(NotificationLanguage $language)/*: void*/ {
		$date = new ilDateTime(time(), IL_CAL_UNIX);

		if (empty($language->getId())) {
			$language->setCreatedAt($date);
		}

		$language->setUpdatedAt($date);

		self::dic()->database()->store($this->language_class::TABLE_NAME, [
			"notification_id" => [ ilDBConstants::T_INTEGER, $language->getNotificationId() ],
			"language" => [ ilDBConstants::T_TEXT, $language->getLanguage() ],
			"subject" => [ ilDBConstants::T_TEXT, $language->getSubject() ],
			"text" => [ ilDBConstants::T_TEXT, $language->getText() ],
			"created_at" => [ ilDBConstants::T_TEXT, $language->getCreatedAt()->get(IL_CAL_DATETIME) ],
			"updated_at" => [ ilDBConstants::T_TEXT, $language->getUpdatedAt()->get(IL_CAL_DATETIME) ]
		], "id", $language->getId());
	}
}
