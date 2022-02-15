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
use Rebelo\Reports\Report\AReport;
use Rebelo\Reports\Report\Datasource\Database;
use Rebelo\Reports\Report\Xml;

/**
 * Class CvsTest
 *
 * @author João Rebelo
 */
class XmlTest extends TestCase
{

    /**
     * @throws \Rebelo\Reports\Report\SerializeReportException
     * @throws \Rebelo\Reports\Report\ReportException
     */
    public function testSetGet()
    {
        $inst = "\Rebelo\Reports\Report\Xml";
        $xml  = new Xml();
        $this->assertInstanceOf($inst, $xml);
        $this->assertNull($xml->getJasperFile());
        $this->assertNull($xml->getOutputFile());
        $this->assertNull($xml->getDatasource());

        $pathOut = "path for output file";
        $xml->setOutputFile($pathOut);
        $this->assertEquals($pathOut, $xml->getOutputFile());

        $xml->setDatasource(new Database());
        $this->assertInstanceOf(
            "\Rebelo\Reports\Report\Datasource\Database",
            $xml->getDatasource()
        );

        $node = new \SimpleXMLElement("<root></root>", LIBXML_NOCDATA);
        $xml->createXmlNode($node);
        $xmlStr = simplexml_load_string($node->asXML());
        $this->assertEquals($pathOut, $xmlStr->xml->{AReport::NODE_OUT_FILE});
    }
}
