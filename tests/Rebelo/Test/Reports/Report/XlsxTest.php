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
use Rebelo\Reports\Report\Xlsx;
use Rebelo\Reports\Report\JasperFile;

/**
 * Class CvsTest
 *
 * @author João Rebelo
 */
class XlsxTest
    extends TestCase
{

    public function testSetGet() : void
    {
        $xlsx = new Xlsx();
        $this->assertInstanceOf("\Rebelo\Reports\Report\Xlsx", $xlsx);
        $this->assertNull($xlsx->getJasperFile());
        $this->assertNull($xlsx->getOutputfile());
        $this->assertNull($xlsx->getDatasource());

        $pathJasper = "path jasper file";
        $xlsx->setJasperFile(new JasperFile($pathJasper));
        $this->assertEquals($pathJasper, $xlsx->getJasperFile()?->getPath());

        $pathOut = "path for output file";
        $xlsx->setOutputfile($pathOut);
        $this->assertEquals($pathOut, $xlsx->getOutputfile());

        $xlsx->setDatasource(new \Rebelo\Reports\Report\Datasource\Database());
        $this->assertInstanceOf(
            "\Rebelo\Reports\Report\Datasource\Database",
            $xlsx->getDatasource()
        );

        $node = new \SimpleXMLElement("<root></root>", LIBXML_NOCDATA);
        $xlsx->createXmlNode($node);
        if(false === $xml  = simplexml_load_string($node->asXML())){ /** @phpstan-ignore-line */
            $this->fail("fail load xml string");
        }
        $this->assertEquals($pathOut, (string)$xml->xlsx->{Xlsx::NODE_OUT_FILE});
    }

}
