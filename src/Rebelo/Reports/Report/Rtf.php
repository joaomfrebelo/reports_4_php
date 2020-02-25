<?php

namespace Rebelo\Reports\Report;

/**
 * Class representing Rtf report
 *
 * Properties file to export the report to a Rtf file
 */
class Rtf
    extends AFileReport
{

    /**
     * Properties file to export the report to a Rtf file
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
