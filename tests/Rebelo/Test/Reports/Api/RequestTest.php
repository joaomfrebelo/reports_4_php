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
use Rebelo\Reports\Report\Api\Action;
use Rebelo\Reports\Report\Api\Request;
use Rebelo\Reports\Report\Api\Status;

class RequestTest extends TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Rebelo\Reports\Config\ConfigException
     * @throws \Rebelo\Test\Reports\Api\RequestException
     */
    public function testRequest(): void
    {
        $request = new Request();
        $data = $request->request(Action::CUT(), null);
        $this->assertNotEmpty($data);
        $this->assertSame(Status::OK, $data["status"]);
    }

    /**
     * @throws \Rebelo\Enum\EnumException
     * @throws \Rebelo\Reports\Config\ConfigException
     * @throws \Rebelo\Test\Reports\Api\RequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \ReflectionException
     */
    public function testSendPrinterCommand()
    {
        $response = (new Request())->sendPrinterCommand(Action::CUT());
        $this->assertSame(Status::OK, $response->getStatus()->get());
    }

}
