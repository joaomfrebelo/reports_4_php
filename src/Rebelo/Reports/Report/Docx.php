<?php

declare(strict_types=1);

namespace Rebelo\Reports\Report;

/**
 * Class representing Docx report
 *
 * Properties file to export the report to a Docx file
 */
class Docx extends AFileReport
{

    /**
     * Properties file to export the report to a Docx file
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
