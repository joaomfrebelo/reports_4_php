<?php

namespace Rebelo\Reports\Report;

/**
 * Class representing Html report
 *
 * Properties file to export the report to a Html file
 */
class Html extends AFileReport
{

    /**
     * Properties file to export the report to a Html file
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
