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

namespace Rebelo\Test\Reports\Report\Sign;

use PHPUnit\Framework\TestCase;
use Rebelo\Reports\Report\Sign\Certificate;
use Rebelo\Reports\Report\Sign\SignException;

/**
 * Class CertificateTest
 *
 * @author João Rebelo
 */
class CertificateTest extends TestCase
{

    /**
     * @throws \Rebelo\Reports\Report\Sign\SignException
     */
    public function testSetGet()
    {
        $cert = new Certificate();
        $this->assertInstanceOf("\Rebelo\Reports\Report\Sign\Certificate", $cert);
        $this->assertNull($cert->getName());
        $this->assertNull($cert->getPassword());

        $name = "cert name";
        $cert->setName($name);
        $this->assertEquals($name, $cert->getName());

        $pwd = "cert passwd";
        $cert->setPassword($pwd);
        $this->assertEquals($pwd, $cert->getPassword());

        $node = new \SimpleXMLElement("<root></root>", LIBXML_NOCDATA);
        $cert->createXmlNode($node);
        $xml  = simplexml_load_string($node->asXML());
        $this->assertEquals($name, $xml->certificate->name);
        $this->assertEquals($pwd, $xml->certificate->password);
    }


    public function testCertNameEmpty()
    {
        $this->expectException(SignException::class);
        $cert = new Certificate();
        $this->assertInstanceOf("\Rebelo\Reports\Report\Sign\Certificate", $cert);
        $cert->setName("");
    }

    /**
     * @throws \Rebelo\Reports\Report\Sign\SignException
     */
    public function testSetPwdNull()
    {
        $cert = new Certificate();
        $this->assertInstanceOf("\Rebelo\Reports\Report\Sign\Certificate", $cert);
        $this->assertNull($cert->getName());
        $this->assertNull($cert->getPassword());

        $name = "cert name";
        $cert->setName($name);
        $this->assertEquals($name, $cert->getName());

        $pwd = null;
        $cert->setPassword($pwd);
        $this->assertNull($cert->getPassword());

        $node = new \SimpleXMLElement("<root></root>", LIBXML_NOCDATA);
        $cert->createXmlNode($node);
        $xml  = simplexml_load_string($node->asXML());
        $this->assertEquals($name, $xml->certificate->name);
        $this->assertEquals("", $xml->certificate->password);
    }
}
