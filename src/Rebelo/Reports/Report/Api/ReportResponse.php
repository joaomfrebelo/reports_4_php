<?php

/*
 * The MIT License
 *
 * Copyright 2020 João Rebelo.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

declare(strict_types=1);

namespace Rebelo\Reports\Report\Api;

/**
 * Report Response
 * @author João Rebelo
 * @since  3.0.0
 */
class ReportResponse extends AResponse
{

    /**
     * The report response (Only for Rest API request)
     * @param \Rebelo\Reports\Report\Api\Status $status   The response status
     * @param string                            $message  The response message
     * @param string                            $duration The server reports export duration
     * @param string|null                       $report   The generated report as base64 encoded string
     */
    public function __construct(Status $status, string $message, string $duration, protected ?string $report)
    {
        parent::__construct($status, $message, $duration);
        $this->log->debug(
            \sprintf("Report is set: %s", ($this->report ?? "") === "")
        );
    }

    /**
     * Get the generated report as base64 encoded string
     * @return string|null
     */
    public function getReport(): ?string
    {
        return $this->report;
    }
}
