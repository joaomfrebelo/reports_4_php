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
use Rebelo\Reports\Report\Ods;
use Rebelo\Reports\Report\JasperFile;

/**
 * Class CvsTest
 *
 * @author João Rebelo
 */
class OdsTest
    extends TestCase
{

    public function testSetGet() : void
    {
        $ods = new Ods();
        $this->assertInstanceOf("\Rebelo\Reports\Report\Ods", $ods);
        $this->assertNull($ods->getJasperFile());
        $this->assertNull($ods->getOutputfile());
        $this->assertNull($ods->getDatasource());

        $pathJasper = "path jasper file";
        $ods->setJasperFile(new JasperFile($pathJasper));
        $this->assertEquals($pathJasper, $ods->getJasperFile()?->getPath());

        $pathOut = "path for output file";
        $ods->setOutputfile($pathOut);
        $this->assertEquals($pathOut, $ods->getOutputfile());

        $ods->setDatasource(new \Rebelo\Reports\Report\Datasource\Database());
        $this->assertInstanceOf(
            "\Rebelo\Reports\Report\Datasource\Database",
            $ods->getDatasource()
        );

        $node = new \SimpleXMLElement("<root></root>", LIBXML_NOCDATA);
        $ods->createXmlNode($node);
        if(false === $xml  = simplexml_load_string($node->asXML())) { /** @phpstan-ignore-line */
            $this->fail("fail load xml string");
        }
        $this->assertEquals($pathOut, $xml->ods->{Ods::NODE_OUT_FILE});
    }

}
