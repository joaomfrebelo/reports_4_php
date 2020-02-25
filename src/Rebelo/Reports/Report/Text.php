<?php

namespace Rebelo\Reports\Report;

/**
 * Class representing Text report
 *
 * Properties file to export the report to a Text file
 */
class Text
    extends AFileReport
{

    /**
     * Properties file to export the report to a Text file
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
