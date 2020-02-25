<?php

namespace Rebelo\Reports\Report;

/**
 * Class representing Csv report
 *
 * Properties file to export the report to a Csv file
 */
class Csv
    extends AFileReport
{

    /**
     * Properties file to export the report to a Csv file
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * @since 1.0.0
     */
    public function __toString()
    {
        return serialize($this);
    }

}
