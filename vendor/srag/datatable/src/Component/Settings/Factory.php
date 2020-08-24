<?php

namespace srag\DataTableUI\AttendanceList\Component\Settings;

use ILIAS\UI\Component\ViewControl\Pagination;
use srag\DataTableUI\AttendanceList\Component\Settings\Sort\Factory as SortFactory;
use srag\DataTableUI\AttendanceList\Component\Settings\Storage\Factory as StorageFactory;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\AttendanceList\Component\Settings
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Factory
{

    /**
     * @param Pagination $pagination
     *
     * @return Settings
     */
    public function settings(Pagination $pagination);


    /**
     * @return SortFactory
     */
    public function sort();


    /**
     * @return StorageFactory
     */
    public function storage();
}
