<?php

namespace Rebelo\Reports\Report;

/**
 * Class representing Xls report
 *
 * Properties file to export the report to a Xls file
 */
class Xls
    extends AFileReport
{

    /**
     * Properties file to export the report to a Xls file
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
