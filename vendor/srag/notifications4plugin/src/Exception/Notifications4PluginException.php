<?php

namespace srag\Notifications4Plugin\AttendanceList\Exception;

use ilException;

/**
 * Class Notifications4PluginException
 *
 * @package srag\Notifications4Plugin\AttendanceList\Exception
 */
class Notifications4PluginException extends ilException
{

    /**
     * Notifications4PluginException constructor
     *
     * @param string $message
     * @param int    $code
     */
    public function __construct(string $message, int $code = 0)
    {
        parent::__construct($message, $code);
    }
}
