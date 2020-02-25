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
use Rebelo\Reports\Report\Rtf;
use Rebelo\Reports\Report\JasperFile;

/**
 * Class CvsTest
 *
 * @author João Rebelo
 */
class RtfTest
    extends TestCase
{

    protected function setUp()
    {

    }

    protected function tearDown()
    {

    }

    public function testSetGet()
    {
        $rtf = new Rtf();
        $this->assertInstanceOf("\Rebelo\Reports\Report\Rtf", $rtf);
        $this->assertNull($rtf->getJasperFile());
        $this->assertNull($rtf->getOutputfile());
        $this->assertNull($rtf->getDatasource());

        $pathJasper = "path jasper file";
        $rtf->setJasperFile(new JasperFile($pathJasper));
        $this->assertEquals($pathJasper, $rtf->getJasperFile()->getPath());

        $pathOut = "path for output file";
        $rtf->setOutputfile($pathOut);
        $this->assertEquals($pathOut, $rtf->getOutputfile());

        $rtf->setDatasource(new \Rebelo\Reports\Report\Datasource\Database());
        $this->assertInstanceOf("\Rebelo\Reports\Report\Datasource\Database",
                                $rtf->getDatasource());

        $node = new \SimpleXMLElement("<root></root>", LIBXML_NOCDATA);
        $rtf->createXmlNode($node);
        $xml  = simplexml_load_string($node->asXML());
        $this->assertEquals($pathOut, $xml->rtf->{Rtf::NODE_OUT_FILE});
    }

}
