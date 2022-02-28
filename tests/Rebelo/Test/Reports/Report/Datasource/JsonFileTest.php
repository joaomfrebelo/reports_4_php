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

namespace Rebelo\Test\Reports\Report\Datasource;

use PHPUnit\Framework\TestCase;
use Rebelo\Reports\Report\Datasource\JsonFile;

class JsonFileTest extends TestCase
{

    public function testInstance(): void
    {
        $json = \json_encode(["data" => "test"]);
        $jsonFile = new JsonFile();
        $jsonFile->setJson($json);

        $this->assertSame($json, $jsonFile->getJson());
    }

    /**
     * @throws \Rebelo\Reports\Report\ReportException
     */
    public function testFillApiRequestEmpty()
    {
        $data = [];
        $jsonFile = new JsonFile();
        $jsonFile->fillApiRequest($data);
        $this->assertEmpty($data);
    }

    /**
     * @throws \Rebelo\Reports\Report\ReportException
     */
    public function testFillApiRequest()
    {
        $data = [];
        $json = \json_encode(["data" => "test"]);
        $jsonFile = new JsonFile();
        $jsonFile->setJson($json);
        $jsonFile->fillApiRequest($data);

        $this->assertSame(
            \base64_encode($json),
            $data[(new \ReflectionClass($jsonFile))->getShortName()][JsonFile::API_P_JSON]
        );
    }
}
