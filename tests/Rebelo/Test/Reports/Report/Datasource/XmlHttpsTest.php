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
use Rebelo\Reports\Report\Datasource\DatasourceException;
use Rebelo\Reports\Report\Datasource\XmlHttps;
use Rebelo\Reports\Report\Datasource\RequestType;

/**
 * Class XmlHttpsTest
 *
 * @author João Rebelo
 */
class XmlHttpsTest extends TestCase
{

    /**
     * @throws \Rebelo\Reports\Report\Datasource\DatasourceException
     */
    public function testSetSetGet()
    {
        $xmlHttps = new XmlHttps();

        $this->assertInstanceOf(
            "Rebelo\Reports\Report\Datasource\XmlHttps",
            $xmlHttps
        );

        $this->assertNull($xmlHttps->getDatePattern());
        $datePattern = "yyyy-MM-dd";
        $selfDate    = $xmlHttps->setDatePattern($datePattern);
        $this->assertInstanceOf(get_class($xmlHttps), $selfDate);
        $this->assertEquals($datePattern, $xmlHttps->getDatePattern());

        $this->assertNull($xmlHttps->getNumberPattern());
        $numberPattern = "#0,0";
        $selfNum       = $xmlHttps->setNumberPattern($numberPattern);
        $this->assertInstanceOf(get_class($xmlHttps), $selfNum);
        $this->assertEquals($numberPattern, $xmlHttps->getNumberPattern());

        $this->assertNull($xmlHttps->getUrl());
        $url     = "https://test.example";
        $selfUrl = $xmlHttps->setUrl($url);
        $this->assertInstanceOf(get_class($xmlHttps), $selfUrl);
        $this->assertEquals($url, $xmlHttps->getUrl());

        $this->assertEquals(RequestType::GET, $xmlHttps->getType()->get());
        $typePost = new RequestType(RequestType::POST);
        $selfType = $xmlHttps->setType($typePost);
        $this->assertInstanceOf(get_class($xmlHttps), $selfType);
        $this->assertEquals($typePost->get(), $xmlHttps->getType()->get());
        $typeGet  = new RequestType(RequestType::GET);
        $xmlHttps->setType($typeGet);
        $this->assertEquals($typeGet->get(), $xmlHttps->getType()->get());
    }

    /**
     * @throws \Rebelo\Reports\Report\Datasource\DatasourceException
     */
    public function testInstance()
    {
        $url      = "https://test.example";
        $typePost = new RequestType(RequestType::POST);
        $xmlHttps = new XmlHttps($url, $typePost);
        $this->assertEquals($url, $xmlHttps->getUrl());
        $this->assertEquals($typePost->get(), $xmlHttps->getType()->get());
    }

    public function testWrongSchema()
    {
        $this->expectException(DatasourceException::class);
        new XmlHttps("http://test.example");
    }

    public function testWrongSetSchema()
    {
        $this->expectException(DatasourceException::class);
        $xmlHttps = new XmlHttps();
        $xmlHttps->setUrl("http://test.example");
    }
}
