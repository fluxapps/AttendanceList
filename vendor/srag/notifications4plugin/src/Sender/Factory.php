<?php

namespace srag\Notifications4Plugin\AttendanceList\Sender;

use srag\DIC\AttendanceList\DICTrait;
use srag\Notifications4Plugin\AttendanceList\Utils\Notifications4PluginTrait;

/**
 * Class Factory
 *
 * @package srag\Notifications4Plugin\AttendanceList\Sender
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Factory implements FactoryInterface
{

    use DICTrait;
    use Notifications4PluginTrait;

    /**
     * @var FactoryInterface|null
     */
    protected static $instance = null;


    /**
     * Factory constructor
     */
    private function __construct()
    {

    }


    /**
     * @return FactoryInterface
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
    public function externalMail($from = "", $to = "")
    {
        return new ExternalMailSender($from, $to);
    }


    /**
     * @inheritDoc
     */
    public function internalMail($user_from = 0, $user_to = "")
    {
        return new InternalMailSender($user_from, $user_to);
    }


    /**
     * @inheritDoc
     */
    public function vcalendar($user_from = 0, $to = "", $method = vcalendarSender::METHOD_REQUEST, $uid = "", $startTime = 0, $endTime = 0, $sequence = 0)
    {
        return new vcalendarSender($user_from, $to, $method, $uid, $startTime, $endTime, $sequence);
    }
}
