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
use Rebelo\Reports\Report\Datasource\Database;
use Rebelo\Reports\Report\Printer;
use Rebelo\Reports\Report\JasperFile;

/**
 * Class CvsTest
 *
 * @author João Rebelo
 */
class PrinterTest extends TestCase
{

    /**
     * @throws \Rebelo\Reports\Report\ReportException
     */
    public function testSetGet()
    {
        $inst    = "\Rebelo\Reports\Report\Printer";
        $printer = new Printer();
        $this->assertInstanceOf($inst, $printer);
        $this->assertNull($printer->getJasperFile());
        $this->assertEquals("", $printer->getPrinter());
        $this->assertNull($printer->getDatasource());

        $pathJasper = "path jasper file";
        $printer->setJasperFile(new JasperFile($pathJasper));
        $this->assertEquals($pathJasper, $printer->getJasperFile()->getPath());

        $printerName = "printer name";
        $printer->setPrinter($printerName);
        $this->assertEquals($printerName, $printer->getPrinter());

        $printer->setDatasource(new Database());
        $this->assertInstanceOf(
            "\Rebelo\Reports\Report\Datasource\Database",
            $printer->getDatasource()
        );

        $node = new \SimpleXMLElement("<root></root>", LIBXML_NOCDATA);
        $printer->createXmlNode($node);
        $xml = simplexml_load_string($node->asXML());
        $this->assertEquals($printerName, $xml->print->printer);
    }
}
