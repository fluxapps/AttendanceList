<?php

namespace srag\DataTableUI\AttendanceList\Component\Settings\Storage;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\AttendanceList\Component\Settings\Storage
 */
interface Factory
{

    /**
     * @return SettingsStorage
     */
    public function default() : SettingsStorage;
}
