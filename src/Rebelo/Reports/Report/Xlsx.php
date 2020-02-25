<?php

namespace Rebelo\Reports\Report;

/**
 * Class representing Xlsx report
 *
 * Properties file to export the report to a Xlsx file
 */
class Xlsx
    extends AFileReport
{

    /**
     * Properties file to export the report to a Xlsx file
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
