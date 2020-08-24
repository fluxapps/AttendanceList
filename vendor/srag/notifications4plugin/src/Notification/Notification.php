<?php

namespace srag\Notifications4Plugin\AttendanceList\Notification;

use ActiveRecord;
use arConnector;
use ilDateTime;
use ILIAS\UI\Component\Component;
use srag\CustomInputGUIs\AttendanceList\TabsInputGUI\MultilangualTabsInputGUI;
use srag\DIC\AttendanceList\DICTrait;
use srag\Notifications4Plugin\AttendanceList\Parser\twigParser;
use srag\Notifications4Plugin\AttendanceList\Utils\Notifications4PluginTrait;

/**
 * Class Notification
 *
 * @package srag\Notifications4Plugin\AttendanceList\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
class Notification extends ActiveRecord implements NotificationInterface
{

    use DICTrait;
    use Notifications4PluginTrait;

    const TABLE_NAME_SUFFIX = "not";
    /**
     * @var ilDateTime
     *
     * @con_has_field    true
     * @con_fieldtype    timestamp
     * @con_is_notnull   true
     */
    protected $created_at;
    /**
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_length       4000
     * @con_is_notnull   true
     */
    protected $description = "";
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
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_length       1024
     * @con_is_notnull   true
     * @con_is_unique    true
     */
    protected $name = "";
    /**
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $parser = twigParser::class;
    /**
     * @var array
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $parser_options = self::DEFAULT_PARSER_OPTIONS;
    /**
     * @var array
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $subject = [];
    /**
     * @var array
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $text = [];
    /**
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_length       1024
     * @con_is_notnull   true
     */
    protected $title = "";
    /**
     * @var ilDateTime
     *
     * @con_has_field    true
     * @con_fieldtype    timestamp
     * @con_is_notnull   true
     */
    protected $updated_at;


    /**
     * Notification constructor
     *
     * @param int              $primary_key_value
     * @param arConnector|null $connector
     */
    public function __construct(/*int*/ $primary_key_value = 0, /*?*/ arConnector $connector = null)
    {
        //parent::__construct($primary_key_value, $connector);
    }


    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return self::notifications4plugin()->getTableNamePrefix() . "_" . self::TABLE_NAME_SUFFIX;
    }


    /**
     * @inheritDoc
     *
     * @deprecated
     */
    public static function returnDbTableName()
    {
        return self::getTableName();
    }


    /**
     * @return Component[]
     */
    public function getActions()
    {
        self::dic()->ctrl()->setParameterByClass(NotificationCtrl::class, NotificationCtrl::GET_PARAM_NOTIFICATION_ID, $this->id);

        return [
            self::dic()->ui()->factory()->link()->standard(self::notifications4plugin()->getPlugin()->translate("edit", NotificationsCtrl::LANG_MODULE),
                self::dic()->ctrl()->getLinkTargetByClass(NotificationCtrl::class, NotificationCtrl::CMD_EDIT_NOTIFICATION, "", false, false)),
            self::dic()->ui()->factory()->link()->standard(self::notifications4plugin()->getPlugin()->translate("duplicate", NotificationsCtrl::LANG_MODULE),
                self::dic()->ctrl()->getLinkTargetByClass(NotificationCtrl::class, NotificationCtrl::CMD_DUPLICATE_NOTIFICATION, "", false, false)),
            self::dic()->ui()->factory()->link()->standard(self::notifications4plugin()->getPlugin()->translate("delete", NotificationsCtrl::LANG_MODULE),
                self::dic()->ctrl()->getLinkTargetByClass(NotificationCtrl::class, NotificationCtrl::CMD_DELETE_NOTIFICATION_CONFIRM, "", false, false))
        ];
    }


    /**
     * @inheritDoc
     */
    public function getConnectorContainerName()
    {
        return self::getTableName();
    }


    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }


    /**
     * @inheritDoc
     */
    public function setCreatedAt(ilDateTime $created_at)
    {
        $this->created_at = $created_at;
    }


    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * @inheritDoc
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }


    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @inheritDoc
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * @inheritDoc
     */
    public function getParser()
    {
        return $this->parser;
    }


    /**
     * @inheritDoc
     */
    public function setParser($parser)
    {
        $this->parser = $parser;
    }


    /**
     * @inheritDoc
     */
    public function getParserOption($key)
    {
        return $this->parser_options[$key];
    }


    /**
     * @inheritDoc
     */
    public function getParserOptions()
    {
        return $this->parser_options;
    }


    /**
     * @inheritDoc
     */
    public function setParserOptions(array $parser_options = self::DEFAULT_PARSER_OPTIONS)
    {
        if (empty($parser_options)) {
            $parser_options = self::DEFAULT_PARSER_OPTIONS;
        }

        $this->parser_options = $parser_options;
    }


    /**
     * @inheritDoc
     */
    public function getSubject(?string $lang_key = null, $use_default_if_not_set = true)
    {
        return strval(MultilangualTabsInputGUI::getValueForLang($this->subject, $lang_key, "subject", $use_default_if_not_set));
    }


    /**
     * @inheritDoc
     */
    public function setSubject($subject, $lang_key)
    {
        MultilangualTabsInputGUI::setValueForLang($this->subject, $subject, $lang_key, "subject");
    }


    /**
     * @inheritDoc
     */
    public function getSubjects()
    {
        return $this->subject;
    }


    /**
     * @inheritDoc
     */
    public function getText(?string $lang_key = null, $use_default_if_not_set = true)
    {
        return strval(MultilangualTabsInputGUI::getValueForLang($this->text, $lang_key, "text", $use_default_if_not_set));
    }


    /**
     * @inheritDoc
     */
    public function setText($text, $lang_key)
    {
        MultilangualTabsInputGUI::setValueForLang($this->text, $text, $lang_key, "text");
    }


    /**
     * @inheritDoc
     */
    public function getTexts()
    {
        return $this->text;
    }


    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * @inheritDoc
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }


    /**
     * @inheritDoc
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }


    /**
     * @inheritDoc
     */
    public function setUpdatedAt(ilDateTime $updated_at)
    {
        $this->updated_at = $updated_at;
    }


    /**
     * @inheritDoc
     */
    public function setParserOption($key, $value)
    {
        $this->parser_options[$key] = $value;
    }


    /**
     * @inheritDoc
     */
    public function setSubjects(array $subjects)
    {
        $this->subject = $subjects;
    }


    /**
     * @inheritDoc
     */
    public function setTexts(array $texts)
    {
        $this->text = $texts;
    }


    /**
     * @inheritDoc
     */
    public function sleep(/*string*/ $field_name)
    {
        $field_value = $this->{$field_name};

        switch ($field_name) {
            case "subject":
            case "text":
            case "parser_options":
                return json_encode($field_value);

            default:
                return parent::sleep($field_name);
        }
    }


    /**
     * @inheritDoc
     */
    public function wakeUp(/*string*/ $field_name, $field_value)
    {
        switch ($field_name) {
            case "subject":
            case "text":
            case "parser_options":
                return json_decode($field_value, true);

            default:
                return parent::wakeUp($field_name, $field_value);
        }
    }
}
