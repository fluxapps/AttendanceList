<?php

namespace srag\DataTableUI\AttendanceList\Component\Format;

use srag\DataTableUI\AttendanceList\Component\Data\Data;
use srag\DataTableUI\AttendanceList\Component\Settings\Settings;
use srag\DataTableUI\AttendanceList\Component\Table;

/**
 * Interface Format
 *
 * @package srag\DataTableUI\AttendanceList\Component\Format
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Format
{

    /**
     * @var string
     */
    const FORMAT_BROWSER = "browser";
    /**
     * @var string
     */
    const FORMAT_CSV = "csv";
    /**
     * @var string
     */
    const FORMAT_EXCEL = "excel";
    /**
     * @var string
     */
    const FORMAT_PDF = "pdf";
    /**
     * @var string
     */
    const FORMAT_HTML = "html";
    /**
     * @var int
     */
    const OUTPUT_TYPE_PRINT = 1;
    /**
     * @var int
     */
    const OUTPUT_TYPE_DOWNLOAD = 2;


    /**
     * @return string
     */
    public function getFormatId();


    /**
     * @param Table $component
     *
     * @return string
     */
    public function getDisplayTitle(Table $component);


    /**
     * @return int
     */
    public function getOutputType();


    /**
     * @return object
     */
    public function getTemplate();


    /**
     * @param Table     $component
     * @param Data|null $data
     * @param Settings  $settings
     *
     * @return string
     */
    public function render(Table $component, ?Data $data, Settings $settings);


    /**
     * @param string $data
     * @param Table  $component
     */
    public function deliverDownload($data, Table $component);
}
