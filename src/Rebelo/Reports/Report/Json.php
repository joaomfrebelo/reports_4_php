<?php

namespace Rebelo\Reports\Report;

/**
 * Class representing Json report
 *
 * Properties file to export the report to a Json file
 */
class Json
    extends AFileReport
{

    /**
     * Properties file to export the report to a Json file
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
