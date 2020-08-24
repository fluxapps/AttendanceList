<?php

namespace srag\DataTableUI\AttendanceList\Component\Column\Formatter;

use srag\DataTableUI\AttendanceList\Component\Column\Formatter\Actions\Factory as ActionsFactory;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\AttendanceList\Component\Column\Formatter
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Factory
{

    /**
     * @return ActionsFactory
     */
    public function actions();


    /**
     * @param array $chain
     *
     * @return Formatter
     */
    public function chainGetter(array $chain);


    /**
     * @return Formatter
     */
    public function check();


    /**
     * @return Formatter
     */
    public function date();


    /**
     * @return Formatter
     */
    public function default();


    /**
     * @param string $prefix
     *
     * @return Formatter
     */
    public function languageVariable($prefix);


    /**
     * @return Formatter
     */
    public function learningProgress();


    /**
     * @return Formatter
     */
    public function link();
}
