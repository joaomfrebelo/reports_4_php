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

namespace Rebelo\Test\Reports\Report;

use PHPUnit\Framework\TestCase;
use Rebelo\Reports\Report\Datasource\JsonHttp;
use Rebelo\Reports\Report\Datasource\RequestType;

/**
 * Class JsonHttpTest
 *
 * @author João Rebelo
 */
class JsonHttpTest
    extends TestCase
{

    protected $_object;

    protected function setUp()
    {

    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    public function testSetSetGet()
    {
        $jsonHttp = new JsonHttp();

        $this->assertInstanceOf("Rebelo\Reports\Report\Datasource\JsonHttp",
                                $jsonHttp);

        $this->assertNull($jsonHttp->getDatePattern());
        $datePattern = "yyyy-MM-dd";
        $selfDate    = $jsonHttp->setDatePattern($datePattern);
        $this->assertInstanceOf(get_class($jsonHttp), $selfDate);
        $this->assertEquals($datePattern, $jsonHttp->getDatePattern());

        $this->assertNull($jsonHttp->getNumberPattern());
        $numberPattern = "#0,0";
        $selfNum       = $jsonHttp->setNumberPattern($numberPattern);
        $this->assertInstanceOf(get_class($jsonHttp), $selfNum);
        $this->assertEquals($numberPattern, $jsonHttp->getNumberPattern());

        $this->assertNull($jsonHttp->getUrl());
        $url     = "http://test.example";
        $selfUrl = $jsonHttp->setUrl($url);
        $this->assertInstanceOf(get_class($jsonHttp), $selfUrl);
        $this->assertEquals($url, $jsonHttp->getUrl());

        $this->assertEquals(RequestType::GET, $jsonHttp->getType()->get());
        $typePost = new RequestType(RequestType::POST);
        $selfType = $jsonHttp->setType($typePost);
        $this->assertInstanceOf(get_class($jsonHttp), $selfType);
        $this->assertEquals($typePost->get(), $jsonHttp->getType()->get());
        $typeGet  = new RequestType(RequestType::GET);
        $jsonHttp->setType($typeGet);
        $this->assertEquals($typeGet->get(), $jsonHttp->getType()->get());
    }

    public function testInstance()
    {
        $url      = "http://test.example";
        $typePost = new RequestType(RequestType::POST);
        $jsonHttp = new JsonHttp($url, $typePost);
        $this->assertEquals($url, $jsonHttp->getUrl());
        $this->assertEquals($typePost->get(), $jsonHttp->getType()->get());
    }

    /**
     * @expectedException \Rebelo\Reports\Report\Datasource\DatasourceException
     */
    public function testWrongSchema()
    {
        new JsonHttp("https://test.example");
    }

    /**
     * @expectedException \Rebelo\Reports\Report\Datasource\DatasourceException
     */
    public function testWrongSetSchema()
    {
        $jsonHttp = new JsonHttp();
        $jsonHttp->setUrl("https://test.example");
    }

}
