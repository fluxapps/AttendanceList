<?php

namespace srag\Notifications4Plugin\AttendanceList\Parser;

use srag\DIC\AttendanceList\DICTrait;
use srag\Notifications4Plugin\AttendanceList\Exception\Notifications4PluginException;
use srag\Notifications4Plugin\AttendanceList\Notification\NotificationInterface;
use srag\Notifications4Plugin\AttendanceList\Utils\Notifications4PluginTrait;

/**
 * Class Repository
 *
 * @package srag\Notifications4Plugin\AttendanceList\Parser
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository implements RepositoryInterface
{

    use DICTrait;
    use Notifications4PluginTrait;

    /**
     * @var RepositoryInterface|null
     */
    protected static $instance = null;
    /**
     * @var Parser[]
     */
    protected $parsers = [];


    /**
     * Repository constructor
     */
    private function __construct()
    {
        $this->addParser($this->factory()->twig());
    }


    /**
     * @return RepositoryInterface
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @inheritDoc
     */
    public function addParser(Parser $parser)
    {
        $this->parsers[$parser->getClass()] = $parser;
    }


    /**
     * @inheritDoc
     */
    public function dropTables()
    {

    }


    /**
     * @inheritDoc
     */
    public function factory()
    {
        return Factory::getInstance();
    }


    /**
     * @inheritDoc
     */
    public function getParserByClass($parser_class)
    {
        if (isset($this->getPossibleParsers()[$parser_class])) {
            return $this->getPossibleParsers()[$parser_class];
        } else {
            throw new Notifications4PluginException("Invalid parser class $parser_class");
        }
    }


    /**
     * @inheritDoc
     */
    public function getParserForNotification(NotificationInterface $notification)
    {
        return $this->getParserByClass($notification->getParser());
    }


    /**
     * @inheritDoc
     */
    public function getPossibleParsers()
    {
        return $this->parsers;
    }


    /**
     * @inheritDoc
     */
    public function installTables()
    {

    }


    /**
     * @inheritDoc
     */
    public function parseSubject(Parser $parser, NotificationInterface $notification, array $placeholders = [], ?string $language = null)
    {
        return $parser->parse($notification->getSubject($language), $placeholders, $notification->getParserOptions());
    }


    /**
     * @inheritDoc
     */
    public function parseText(Parser $parser, NotificationInterface $notification, array $placeholders = [], ?string $language = null)
    {
        return $parser->parse($notification->getText($language), $placeholders, $notification->getParserOptions());
    }
}
