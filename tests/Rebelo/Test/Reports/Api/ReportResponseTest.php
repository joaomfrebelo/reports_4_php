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

namespace Rebelo\Test\Reports\Api;

use PHPUnit\Framework\TestCase;
use Rebelo\Reports\Report\Api\ReportResponse;
use Rebelo\Reports\Report\Api\Status;

class ReportResponseTest extends TestCase
{

    public function testInstance()
    {
        $message  = "The response message";
        $duration = "9s";
        $report   = "The report";

        foreach ([Status::OK(), Status::ERROR()] as $status) {
            /** @var Status $status */
            $response = new ReportResponse($status, $message, $duration, $report);

            $this->assertSame($status->get(), $response->getStatus()->get());
            $this->assertSame($message, $response->getMessage());
            $this->assertSame($duration, $response->getDuration());
            $this->assertSame($report, $response->getReport());
        }

        $response = new ReportResponse($status, $message, $duration, null);
        $this->assertNull($response->getReport());
    }
}
