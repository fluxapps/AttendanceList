<?php

namespace srag\DIC\AttendanceList;

use srag\DIC\AttendanceList\DIC\DICInterface;
use srag\DIC\AttendanceList\Exception\DICException;
use srag\DIC\AttendanceList\Output\OutputInterface;
use srag\DIC\AttendanceList\Plugin\PluginInterface;
use srag\DIC\AttendanceList\Version\VersionInterface;

/**
 * Interface DICStaticInterface
 *
 * @package srag\DIC\AttendanceList
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface DICStaticInterface {

	/**
	 * Clear cache. Needed for instance in unit tests
	 *
	 * @deprecated
	 */
	public static function clearCache()/*: void*/ ;


	/**
	 * Get DIC interface
	 *
	 * @return DICInterface DIC interface
	 *
	 * @throws DICException DIC not supports ILIAS X.X.X anymore!"
	 */
	public static function dic();


	/**
	 * Get output interface
	 *
	 * @return OutputInterface Output interface
	 */
	public static function output();


	/**
	 * Get plugin interface
	 *
	 * @param string $plugin_class_name
	 *
	 * @return PluginInterface Plugin interface
	 *
	 * @throws DICException Class $plugin_class_name not exists!
	 * @throws DICException Class $plugin_class_name not extends ilPlugin!
	 * @logs   DEBUG Please implement $plugin_class_name::getInstance()!
	 */
	public static function plugin($plugin_class_name);


	/**
	 * Get version interface
	 *
	 * @return VersionInterface Version interface
	 */
	public static function version();
}
