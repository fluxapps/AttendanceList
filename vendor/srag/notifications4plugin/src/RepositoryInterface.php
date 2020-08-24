<?php

namespace srag\Notifications4Plugin\AttendanceList;

use srag\DIC\AttendanceList\Plugin\Pluginable;
use srag\Notifications4Plugin\AttendanceList\Notification\RepositoryInterface as NotificationRepositoryInterface;
use srag\Notifications4Plugin\AttendanceList\Parser\RepositoryInterface as ParserRepositoryInterface;
use srag\Notifications4Plugin\AttendanceList\Sender\RepositoryInterface as SenderRepositoryInterface;

/**
 * Interface RepositoryInterface
 *
 * @package srag\Notifications4Plugin\AttendanceList
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface RepositoryInterface extends Pluginable
{

    /**
     *
     */
    public function dropTables()/* : void*/;


    /**
     * @return array
     */
    public function getPlaceholderTypes() : array;


    /**
     * @return string
     */
    public function getTableNamePrefix() : string;


    /**
     *
     */
    public function installLanguages()/* : void*/;


    /**
     *
     */
    public function installTables()/* : void*/;


    /**
     * @return NotificationRepositoryInterface
     */
    public function notifications() : NotificationRepositoryInterface;


    /**
     * @return ParserRepositoryInterface
     */
    public function parser() : ParserRepositoryInterface;


    /**
     * @return SenderRepositoryInterface
     */
    public function sender() : SenderRepositoryInterface;


    /**
     * @param array $placeholder_types
     *
     * @return self
     */
    public function withPlaceholderTypes(array $placeholder_types) : self;


    /**
     * @param string $table_name_prefix
     *
     * @return self
     */
    public function withTableNamePrefix(string $table_name_prefix) : self;
}
