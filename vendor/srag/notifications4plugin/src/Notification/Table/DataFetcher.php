<?php

namespace srag\Notifications4Plugin\AttendanceList\Notification\Table;

use srag\DataTableUI\AttendanceList\Component\Data\Data;
use srag\DataTableUI\AttendanceList\Component\Data\Row\RowData;
use srag\DataTableUI\AttendanceList\Component\Settings\Settings;
use srag\DataTableUI\AttendanceList\Implementation\Data\Fetcher\AbstractDataFetcher;
use srag\Notifications4Plugin\AttendanceList\Notification\NotificationInterface;
use srag\Notifications4Plugin\AttendanceList\Utils\Notifications4PluginTrait;

/**
 * Class DataFetcher
 *
 * @package srag\Notifications4Plugin\AttendanceList\Notification\Table
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class DataFetcher extends AbstractDataFetcher
{

    use Notifications4PluginTrait;

    /**
     * @inheritDoc
     */
    public function fetchData(Settings $settings) : Data
    {
        return self::dataTableUI()->data()->data(array_map(function (NotificationInterface $notification
        ) : RowData {
            return self::dataTableUI()->data()->row()->getter($notification->getId(), $notification);
        }, self::notifications4plugin()->notifications()->getNotifications($settings)),
            self::notifications4plugin()->notifications()->getNotificationsCount());
    }
}
