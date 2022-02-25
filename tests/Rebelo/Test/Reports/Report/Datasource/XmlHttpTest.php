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
use Rebelo\Reports\Report\Datasource\AServer;
use Rebelo\Reports\Report\Datasource\DatasourceException;
use Rebelo\Reports\Report\Datasource\XmlHttp;
use Rebelo\Reports\Report\Datasource\RequestType;

/**
 * Class XmlHttpTest
 *
 * @author João Rebelo
 */
class XmlHttpTest extends TestCase
{

    /**
     * @throws \Rebelo\Reports\Report\Datasource\DatasourceException
     */
    public function testSetSetGet()
    {
        $xmlHttp = new XmlHttp();

        $this->assertInstanceOf(
            "Rebelo\Reports\Report\Datasource\XmlHttp",
            $xmlHttp
        );

        $this->assertNull($xmlHttp->getDatePattern());
        $datePattern = "yyyy-MM-dd";
        $selfDate    = $xmlHttp->setDatePattern($datePattern);
        $this->assertInstanceOf(get_class($xmlHttp), $selfDate);
        $this->assertEquals($datePattern, $xmlHttp->getDatePattern());

        $this->assertNull($xmlHttp->getNumberPattern());
        $numberPattern = "#0,0";
        $selfNum       = $xmlHttp->setNumberPattern($numberPattern);
        $this->assertInstanceOf(get_class($xmlHttp), $selfNum);
        $this->assertEquals($numberPattern, $xmlHttp->getNumberPattern());

        $this->assertNull($xmlHttp->getUrl());
        $url     = "http://test.example";
        $selfUrl = $xmlHttp->setUrl($url);
        $this->assertInstanceOf(get_class($xmlHttp), $selfUrl);
        $this->assertEquals($url, $xmlHttp->getUrl());

        $this->assertEquals(RequestType::GET, $xmlHttp->getType()->get());
        $typePost = new RequestType(RequestType::POST);
        $selfType = $xmlHttp->setType($typePost);
        $this->assertInstanceOf(get_class($xmlHttp), $selfType);
        $this->assertEquals($typePost->get(), $xmlHttp->getType()->get());
        $typeGet  = new RequestType(RequestType::GET);
        $xmlHttp->setType($typeGet);
        $this->assertEquals($typeGet->get(), $xmlHttp->getType()->get());
    }

    /**
     * @throws \Rebelo\Reports\Report\Datasource\DatasourceException
     */
    public function testInstance()
    {
        $url      = "http://test.example";
        $typePost = new RequestType(RequestType::POST);
        $xmlHttp  = new XmlHttp($url, $typePost);
        $this->assertEquals($url, $xmlHttp->getUrl());
        $this->assertEquals($typePost->get(), $xmlHttp->getType()->get());
    }


    public function testWrongSchema()
    {
        $this->expectException(DatasourceException::class);
        new XmlHttp("https://test.example");
    }

    public function testWrongSetSchema()
    {
        $this->expectException(DatasourceException::class);
        $xmlHttp = new XmlHttp();
        $xmlHttp->setUrl("https://test.example");
    }

    /**
     * @throws \Rebelo\Reports\Report\Datasource\DatasourceException
     */
    public function testFillApiRequest(): void
    {
        $data = [];
        $xmlHttp = new XmlHttp("http://localhost:4999", new RequestType(RequestType::GET));
        $xmlHttp->setDatePattern("Y-m-d");
        $xmlHttp->setNumberPattern("0#.##");
        $xmlHttp->fillApiRequest($data);

        $api = $data[(new \ReflectionClass(XmlHttp::class))->getShortName()];

        $this->assertSame(
            $xmlHttp->getUrl(),
            $api[AServer::API_P_URL]
        );

        $this->assertSame(
            $xmlHttp->getType()->get(),
            $api[AServer::API_P_TYPE]
        );

        $this->assertSame(
            $xmlHttp->getDatePattern(),
            $api[AServer::API_P_DATE_PATTERN]
        );

        $this->assertSame(
            $xmlHttp->getNumberPattern(),
            $api[AServer::API_P_NUMBER_PATTERN]
        );
    }
}
