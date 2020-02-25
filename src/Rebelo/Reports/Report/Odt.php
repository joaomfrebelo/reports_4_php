<?php

namespace Rebelo\Reports\Report;

/**
 * Class representing Odt report
 *
 * Properties file to export the report to a Odt file
 */
class Odt
    extends AFileReport
{

    /**
     * Properties file to export the report to a Odt file
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
