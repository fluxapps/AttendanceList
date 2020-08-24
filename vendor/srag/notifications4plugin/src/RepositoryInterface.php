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
    public function dropTables();


    /**
     * @return array
     */
    public function getPlaceholderTypes();


    /**
     * @return string
     */
    public function getTableNamePrefix();


    /**
     *
     */
    public function installLanguages();


    /**
     *
     */
    public function installTables();


    /**
     * @return NotificationRepositoryInterface
     */
    public function notifications();


    /**
     * @return ParserRepositoryInterface
     */
    public function parser();


    /**
     * @return SenderRepositoryInterface
     */
    public function sender();


    /**
     * @param array $placeholder_types
     *
     * @return self
     */
    public function withPlaceholderTypes(array $placeholder_types);


    /**
     * @param string $table_name_prefix
     *
     * @return self
     */
    public function withTableNamePrefix($table_name_prefix);
}
