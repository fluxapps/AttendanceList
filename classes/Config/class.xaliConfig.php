<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class xaliConfig
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class xaliConfig extends ActiveRecord {

	const F_INTERVAL_REMINDER_EMAIL = 'interval_reminder_email';
	const F_SENDER_REMINDER_EMAIL = 'sender_reminder_email';
	const F_HTTP_PATH = 'http_path';
    const F_SHOW_NOT_RELEVANT = 'show_not_relevant';
    const F_SHOW_PRESENT_TOTAL = 'show_present_total';
	const TABLE_NAME = 'xali_config';


	/**
	 * @return string
	 */
	static function returnDbTableName(): string
    {
		return self::TABLE_NAME;
	}

	protected static array $cache = array();
	protected static array $cache_loaded = array();

	public static function getConfig($name): mixed
    {
		if (!self::$cache_loaded[$name]) {
			try {
				$obj = new self($name);
			} catch (Exception $e) {
				$obj = new self();
				$obj->setName($name);
			}
			self::$cache[$name] = json_decode($obj->getValue(), true);
			self::$cache_loaded[$name] = true;
		}

		return self::$cache[$name];
	}

	public static function set($name, $value): void
    {
		try {
			$obj = new self($name);
		} catch (Exception $e) {
			$obj = new self();
			$obj->setName($name);
		}
		$obj->setValue(json_encode($value));

		if (self::where(array( 'name' => $name ))->hasSets()) {
			$obj->update();
		} else {
			$obj->create();
		}
	}


	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_is_unique        true
	 * @db_is_primary       true
	 * @db_is_notnull       true
	 * @db_fieldtype        text
	 * @db_length           250
	 */
	protected string $name;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           4000
	 */
	protected string $value;

	public function setName(string $name): void
    {
		$this->name = $name;
	}

	public function getName(): string
    {
		return $this->name;
	}

	public function setValue(string $value): void
    {
		$this->value = $value;
	}

	public function getValue(): string
    {
		return $this->value;
	}
}