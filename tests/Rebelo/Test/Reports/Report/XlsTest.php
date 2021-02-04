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
use Rebelo\Reports\Report\Xls;
use Rebelo\Reports\Report\JasperFile;

/**
 * Class CvsTest
 *
 * @author João Rebelo
 */
class XlsTest
    extends TestCase
{

    public function testSetGet() : void
    {
        $xls = new Xls();
        $this->assertInstanceOf("\Rebelo\Reports\Report\Xls", $xls);
        $this->assertNull($xls->getJasperFile());
        $this->assertNull($xls->getOutputfile());
        $this->assertNull($xls->getDatasource());

        $pathJasper = "path jasper file";
        $xls->setJasperFile(new JasperFile($pathJasper));
        $this->assertEquals($pathJasper, $xls->getJasperFile()?->getPath());

        $pathOut = "path for output file";
        $xls->setOutputfile($pathOut);
        $this->assertEquals($pathOut, $xls->getOutputfile());

        $xls->setDatasource(new \Rebelo\Reports\Report\Datasource\Database());
        $this->assertInstanceOf(
            "\Rebelo\Reports\Report\Datasource\Database",
            $xls->getDatasource()
        );

        $node = new \SimpleXMLElement("<root></root>", LIBXML_NOCDATA);
        $xls->createXmlNode($node);
        if(false === $xml  = simplexml_load_string($node->asXML())){ /** @phpstan-ignore-line */
            $this->fail("fail load xml string");
        }
        $this->assertEquals($pathOut, (string)$xml->xls->{Xls::NODE_OUT_FILE});
    }

}
