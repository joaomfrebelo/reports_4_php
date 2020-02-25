<?php

namespace Rebelo\Reports\Report;

/**
 * Class representing Ods report
 *
 * Properties file to export the report to a Ods file
 */
class Ods
    extends AFileReport
{

    /**
     * Properties file to export the report to a Ods file
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
