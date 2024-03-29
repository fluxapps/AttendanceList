<?php

namespace srag\DataTableUI\AttendanceList\Implementation\Data\Fetcher;

use srag\DataTableUI\AttendanceList\Component\Data\Fetcher\DataFetcher;
use srag\DataTableUI\AttendanceList\Component\Table;
use srag\DataTableUI\AttendanceList\Implementation\Utils\DataTableUITrait;
use srag\DIC\AttendanceList\DICTrait;

/**
 * Class AbstractDataFetcher
 *
 * @package srag\DataTableUI\AttendanceList\Implementation\Data\Fetcher
 */
abstract class AbstractDataFetcher implements DataFetcher
{

    use DICTrait;
    use DataTableUITrait;

    /**
     * AbstractDataFetcher constructor
     */
    public function __construct()
    {

    }


    /**
     * @inheritDoc
     */
    public function getNoDataText(Table $component) : string
    {
        return $component->getPlugin()->translate("no_data", Table::LANG_MODULE);
    }


    /**
     * @inheritDoc
     */
    public function isFetchDataNeedsFilterFirstSet() : bool
    {
        return false;
    }
}
