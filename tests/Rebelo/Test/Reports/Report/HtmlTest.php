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
use Rebelo\Reports\Report\Html;
use Rebelo\Reports\Report\JasperFile;

/**
 * Class CvsTest
 *
 * @author João Rebelo
 */
class HtmlTest
    extends TestCase
{

    public function testSetGet() : void
    {
        $html = new Html();
        $this->assertInstanceOf("\Rebelo\Reports\Report\Html", $html);
        $this->assertNull($html->getJasperFile());
        $this->assertNull($html->getOutputfile());
        $this->assertNull($html->getDatasource());

        $pathJasper = "path jasper file";
        $html->setJasperFile(new JasperFile($pathJasper));
        $this->assertEquals($pathJasper, $html->getJasperFile()?->getPath());

        $pathOut = "path for output file";
        $html->setOutputfile($pathOut);
        $this->assertEquals($pathOut, $html->getOutputfile());

        $html->setDatasource(new \Rebelo\Reports\Report\Datasource\Database());
        $this->assertInstanceOf(
            "\Rebelo\Reports\Report\Datasource\Database",
            $html->getDatasource()
        );

        $node = new \SimpleXMLElement("<root></root>", LIBXML_NOCDATA);
        $html->createXmlNode($node);
        if(false === $xml  = simplexml_load_string($node->asXML())) { /** @phpstan-ignore-line */
            $this->fail("fail load xml string");
        }
        $this->assertEquals($pathOut, $xml->html->{Html::NODE_OUT_FILE});
    }

}
