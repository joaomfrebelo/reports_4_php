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
use Rebelo\Reports\Report\Sign\Keystore;
use Rebelo\Reports\Report\Sign\Certificate;

/**
 * Class KeystoreTest
 *
 * @author João Rebelo
 */
class KeystoreTest
    extends TestCase
{

    public function testSetGet()
    {
        $inst = "\Rebelo\Reports\Report\Sign\Keystore";
        $key  = new Keystore();
        $this->assertInstanceOf($inst, $key);
        $this->assertNull($key->getCertificate());
        $this->assertNull($key->getPassword());
        $this->assertNull($key->getPath());

        $certName = "cert name";
        $certPwd  = "Cert pwd";
        $cert     = new Certificate();
        $cert->setName($certName);
        $cert->setPassword($certPwd);
        $setCert  = $key->setCertificate($cert);
        $this->assertInstanceOf($inst, $setCert);
        $this->assertInstanceOf("\Rebelo\Reports\Report\Sign\Certificate",
                                $key->getCertificate());

        $pwd    = "key store pwd";
        $setPwd = $key->setPassword($pwd);
        $this->assertInstanceOf($inst, $setPwd);
        $this->assertEquals($pwd, $key->getPassword());

        $path    = "key store path";
        $setPath = $key->setPath($path);
        $this->assertInstanceOf($inst, $setPath);
        $this->assertEquals($path, $key->getPath());

        $node     = new \SimpleXMLElement("<root></root>", LIBXML_NOCDATA);
        $key->createXmlNode($node);
        $xml      = simplexml_load_string($node->asXML());
        $this->assertEquals($path, $xml->keystore->path);
        $this->assertEquals($pwd, $xml->keystore->password);
        $nodeCert = $xml->keystore->certificate;
        $this->assertEquals($certName, $nodeCert->name);
        $this->assertEquals($certPwd, $nodeCert->password);
    }

    /**
     * @expectedException \Rebelo\Reports\Report\SerializeReportException
     */
    public function testCreateNodeNullCert()
    {
        $key  = new Keystore();
        $key->setPassword("pwd");
        $key->setPath("path");
        $node = new \SimpleXMLElement("<root></root>", LIBXML_NOCDATA);
        $key->createXmlNode($node);
    }

    /**
     * @expectedException \Rebelo\Reports\Report\SerializeReportException
     */
    public function testCreateNodeNullPath()
    {
        $key  = new Keystore();
        $key->setPassword("pwd");
        $key->setCertificate(new Certificate());
        $node = new \SimpleXMLElement("<root></root>", LIBXML_NOCDATA);
        $key->createXmlNode($node);
    }

}
