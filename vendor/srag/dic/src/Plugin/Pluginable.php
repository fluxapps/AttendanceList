<?php

namespace srag\DIC\AttendanceList\Plugin;

/**
 * Interface Pluginable
 *
 * @package srag\DIC\AttendanceList\Plugin
 */
interface Pluginable
{

    /**
     * @return PluginInterface
     */
    public function getPlugin() : PluginInterface;


    /**
     * @param PluginInterface $plugin
     *
     * @return static
     */
    public function withPlugin(PluginInterface $plugin)/*: static*/ ;
}
