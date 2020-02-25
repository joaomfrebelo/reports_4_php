<?php

namespace Rebelo\Reports\Report;

/**
 * Class representing Xml report
 *
 * Properties file to export the report to a XML file
 */
class Xml
    extends AFileReport
{

    /**
     * Properties file to export the report to a XML file
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
