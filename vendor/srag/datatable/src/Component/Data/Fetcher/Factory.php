<?php

namespace srag\DataTableUI\AttendanceList\Component\Data\Fetcher;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\AttendanceList\Component\Data\Fetcher
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Factory
{

    /**
     * @param object[] $data
     * @param string   $id_key
     *
     * @return DataFetcher
     */
    public function staticData(array $data, $id_key);
}
