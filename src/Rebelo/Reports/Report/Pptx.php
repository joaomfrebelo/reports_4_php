<?php

namespace Rebelo\Reports\Report;

/**
 * Class representing Pptx report
 *
 * Properties file to export the report to a Pptx file
 */
class Pptx
    extends AFileReport
{

    /**
     * Properties file to export the report to a Pptx file
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
