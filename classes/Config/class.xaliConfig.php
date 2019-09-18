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
    const F_SHOW_NOT_RELEVANT_STATUS = 'show_not_relevant_status';
	const TABLE_NAME = 'xali_config';


	/**
	 * @return string
	 */
	static function returnDbTableName() {
		return self::TABLE_NAME;
	}


	/**
	 * @var array
	 */
	protected static $cache = array();
	/**
	 * @var array
	 */
	protected static $cache_loaded = array();


	/**
	 * @param $name
	 *
	 * @return mixed
	 */
	public static function getConfig($name) {
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


	/**
	 * @param $name
	 * @param $value
	 */
	public static function set($name, $value) {
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
	protected $name;
	/**
	 * @var string
	 *
	 * @db_has_field        true
	 * @db_fieldtype        text
	 * @db_length           4000
	 */
	protected $value;


	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}


	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * @param string $value
	 */
	public function setValue($value) {
		$this->value = $value;
	}


	/**
	 * @return string
	 */
	public function getValue() {
		return $this->value;
	}
}