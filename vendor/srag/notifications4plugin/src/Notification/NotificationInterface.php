<?php

namespace srag\Notifications4Plugin\AttendanceList\Notification;

use ilDateTime;

/**
 * Interface NotificationInterface
 *
 * @package srag\Notifications4Plugin\AttendanceList\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface NotificationInterface
{

    const DEFAULT_PARSER_OPTIONS
        = [
            "autoescape" => false
        ];


    /**
     * @return string
     */
    public static function getTableName();


    /**
     * @return ilDateTime
     */
    public function getCreatedAt();


    /**
     * @return string
     */
    public function getDescription();


    /**
     * @return int
     */
    public function getId();


    /**
     * @return string
     */
    public function getName();


    /**
     * @return string
     */
    public function getParser();


    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getParserOption($key);


    /**
     * @return array
     */
    public function getParserOptions();


    /**
     * @param string|null $lang_key
     * @param bool        $use_default_if_not_set
     *
     * @return string
     */
    public function getSubject(?string $lang_key = null, $use_default_if_not_set = true);


    /**
     * @return array
     */
    public function getSubjects();


    /**
     * @param string|null $lang_key
     * @param bool        $use_default_if_not_set
     *
     * @return string
     */
    public function getText(?string $lang_key = null, $use_default_if_not_set = true);


    /**
     * @return array
     */
    public function getTexts();


    /**
     * @return string
     */
    public function getTitle();


    /**
     * @return ilDateTime
     */
    public function getUpdatedAt();


    /**
     * @param ilDateTime $created_at
     */
    public function setCreatedAt(ilDateTime $created_at);


    /**
     * @param string $description
     */
    public function setDescription($description);


    /**
     * @param int $id
     */
    public function setId($id);


    /**
     * @param string $name
     */
    public function setName($name);


    /**
     * @param string $parser
     */
    public function setParser($parser);


    /**
     * @param string $key
     * @param mixed  $value
     */
    public function setParserOption($key, $value);


    /**
     * @param array $parser_options
     */
    public function setParserOptions(array $parser_options = self::DEFAULT_PARSER_OPTIONS);


    /**
     * @param string $subject
     * @param string $lang_key
     */
    public function setSubject($subject, $lang_key);


    /**
     * @param array $subjects
     */
    public function setSubjects(array $subjects);


    /**
     * @param string $text
     * @param string $lang_key
     */
    public function setText($text, $lang_key);


    /**
     * @param array $texts
     */
    public function setTexts(array $texts);


    /**
     * @param string $title
     */
    public function setTitle($title);


    /**
     * @param ilDateTime $updated_at
     */
    public function setUpdatedAt(ilDateTime $updated_at);
}
