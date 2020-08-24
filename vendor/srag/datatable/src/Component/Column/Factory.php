<?php

namespace srag\DataTableUI\AttendanceList\Component\Column;

use srag\DataTableUI\AttendanceList\Component\Column\Formatter\Factory as FormatterFactory;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\AttendanceList\Component\Column
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Factory
{

    /**
     * @param string $key
     * @param string $title
     *
     * @return Column
     */
    public function column($key, $title);


    /**
     * @return FormatterFactory
     */
    public function formatter();
}
