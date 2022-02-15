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
use Rebelo\Reports\Report\JasperFile;
use Rebelo\Reports\Report\ReportException;
use Rebelo\Reports\Report\SerializeReportException;

/**
 * Class CvsTest
 *
 * @author João Rebelo
 */
class JasperFileTest extends TestCase
{

    /**
     * @throws \Rebelo\Reports\Report\ReportException
     */
    public function testConstructor()
    {
        $copies     = 9;
        $jasperFile = new JasperFile(null, $copies);
        $this->assertInstanceOf("\Rebelo\Reports\Report\JasperFile", $jasperFile);
        $this->assertNull($jasperFile->getPath());
        $this->assertEquals($copies, $jasperFile->getCopies());
    }

    /**
     * @throws \Rebelo\Reports\Report\SerializeReportException
     * @throws \Rebelo\Reports\Report\ReportException
     */
    public function testSetGet()
    {

        $jasperFile = new JasperFile();
        $this->assertInstanceOf("\Rebelo\Reports\Report\JasperFile", $jasperFile);
        $this->assertNull($jasperFile->getPath());
        $this->assertEquals(1, $jasperFile->getCopies());

        $path = "path to jasper file";
        $jasperFile->setPath($path);
        $this->assertEquals($path, $jasperFile->getPath());

        $copies = 2;
        $jasperFile->setCopies($copies);
        $this->assertEquals($copies, $jasperFile->getCopies());

        $node = new \SimpleXMLElement("<root></root>", LIBXML_NOCDATA);
        $jasperFile->createXmlNode($node);
        $xml  = simplexml_load_string($node->asXML());
        $this->assertEquals($path, $xml->jasperfile);
        $this->assertEquals(strval($copies), $xml->jasperfile[0]["copies"]);
    }

    public function testZeroCopiesConstructor()
    {
        $this->expectException(ReportException::class);
        $copies = 0;
        new JasperFile(null, $copies);
    }

    public function testNegativeCopiesConstructor()
    {
        $this->expectException(ReportException::class);
        $copies = -9;
        new JasperFile(null, $copies);
    }

    public function testSetNegativeCopies()
    {
        $this->expectException(ReportException::class);
        $jasper = new JasperFile();
        $jasper->setCopies(-1);
    }

    public function testSetZeroCopies()
    {
        $this->expectException(ReportException::class);
        $jasper = new JasperFile();
        $jasper->setCopies(0);
    }

    public function testGetNodePathNull()
    {
        $this->expectException(SerializeReportException::class);
        $jasper = new JasperFile();
        $jasper->createXmlNode(new \SimpleXMLElement("<root></root>"));
    }
}
