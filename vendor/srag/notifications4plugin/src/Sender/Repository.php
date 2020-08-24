<?php

namespace srag\Notifications4Plugin\AttendanceList\Sender;

use srag\DIC\AttendanceList\DICTrait;
use srag\Notifications4Plugin\AttendanceList\Notification\NotificationInterface;
use srag\Notifications4Plugin\AttendanceList\Utils\Notifications4PluginTrait;

/**
 * Class Repository
 *
 * @package srag\Notifications4Plugin\AttendanceList\Sender
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
     * Repository constructor
     */
    private function __construct()
    {

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
    public function installTables()
    {

    }


    /**
     * @inheritDoc
     */
    public function send(Sender $sender, NotificationInterface $notification, array $placeholders = [], ?string $language = null)
    {
        $parser = self::notifications4plugin()->parser()->getParserForNotification($notification);

        $sender->setSubject(self::notifications4plugin()->parser()->parseSubject($parser, $notification, $placeholders, $language));

        $sender->setMessage(self::notifications4plugin()->parser()->parseText($parser, $notification, $placeholders, $language));

        $sender->send();
    }
}
