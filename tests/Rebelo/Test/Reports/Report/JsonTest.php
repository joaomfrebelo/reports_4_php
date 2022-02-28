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
use Rebelo\Reports\Report\Json;
use Rebelo\Reports\Report\JasperFile;

/**
 * Class CvsTest
 *
 * @author João Rebelo
 */
class JsonTest extends TestCase
{

    /**
     * @throws \Rebelo\Reports\Report\SerializeReportException
     * @throws \Rebelo\Reports\Report\ReportException
     */
    public function testSetGet()
    {
        $json = new Json();
        $this->assertInstanceOf("\Rebelo\Reports\Report\Json", $json);
        $this->assertNull($json->getJasperFile());
        $this->assertNull($json->getOutputFile());
        $this->assertNull($json->getDatasource());

        $pathJasper = "path jasper file";
        $json->setJasperFile(new JasperFile($pathJasper));
        $this->assertEquals($pathJasper, $json->getJasperFile()->getPath());

        $pathOut = "path for output file";
        $json->setOutputFile($pathOut);
        $this->assertEquals($pathOut, $json->getOutputFile());

        $json->setDatasource(new Database());
        $this->assertInstanceOf(
            "\Rebelo\Reports\Report\Datasource\Database",
            $json->getDatasource()
        );

        $node = new \SimpleXMLElement("<root></root>", LIBXML_NOCDATA);
        $json->createXmlNode($node);
        $xml = simplexml_load_string($node->asXML());
        $this->assertEquals($pathOut, $xml->json->{AReport::NODE_OUT_FILE});
    }
}
