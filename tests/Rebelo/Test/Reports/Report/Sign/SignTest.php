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
use Rebelo\Reports\Report\SerializeReportException;
use Rebelo\Reports\Report\Sign\Rectangle;
use Rebelo\Reports\Report\Sign\Keystore;
use Rebelo\Reports\Report\Sign\Certificate;
use Rebelo\Reports\Report\Sign\Level;
use Rebelo\Reports\Report\Sign\Type;
use Rebelo\Reports\Report\Sign\Sign;

/**
 * Class SignTest
 *
 * @author João Rebelo
 */
class SignTest extends TestCase
{

    /**
     * @throws \Rebelo\Enum\EnumException
     * @throws \Rebelo\Reports\Report\SerializeReportException
     * @throws \Rebelo\Reports\Report\Sign\SignException
     */
    public function testSetGet()
    {
        $inst = "\Rebelo\Reports\Report\Sign\Sign";
        $sign = new Sign();
        $this->assertInstanceOf($inst, $sign);
        $this->assertNull($sign->getKeystore());
        $this->assertNull($sign->getLevel());
        $this->assertNull($sign->getLocation());
        $this->assertNull($sign->getReason());
        $this->assertNull($sign->getRectangle());
        $this->assertEquals(Type::SELF_SIGNED, $sign->getType()->get());

        $cert     = new Certificate();
        $certName = "cert name";
        $cert->setName($certName);
        $certPwd  = "cert pwd";
        $cert->setPassword($certPwd);

        $key     = new Keystore();
        $key->setCertificate($cert);
        $keyPwd  = "key pwd";
        $key->setPassword($keyPwd);
        $keyPath = "key path";
        $key->setPath($keyPath);

        $this->assertInstanceOf($inst, $sign->setKeystore($key));
        $this->assertEquals($keyPath, $sign->getKeystore()->getPath());
        $level  = Level::CERTIFIED_NO_CHANGES_ALLOWED;
        $this->assertInstanceOf($inst, $sign->setLevel(new Level($level)));
        $this->assertEquals($level, $sign->getLevel()->get());
        $type   = Type::SELF_SIGNED;
        $this->assertInstanceOf($inst, $sign->setType(new Type($type)));
        $this->assertEquals($type, $sign->getType()->get());
        $local  = "sign location";
        $this->assertInstanceOf($inst, $sign->setLocation($local));
        $this->assertEquals($local, $sign->getLocation());
        $reason = "sign reason";
        $this->assertInstanceOf($inst, $sign->setReason($reason));
        $this->assertEquals($reason, $sign->getReason());
        $this->assertInstanceOf($inst, $sign->setRectangle(new Rectangle()));
        $this->assertEquals(0, $sign->getRectangle()->getRotation());
        $contact = "The contact";
        $this->assertInstanceOf($inst, $sign->setContact($contact));
        $this->assertEquals($contact, $sign->getContact());

        $node = new \SimpleXMLElement("<root></root>", LIBXML_NOCDATA);
        $sign->createXmlNode($node);
        $xml  = simplexml_load_string($node->asXML());
        $this->assertEquals(strval(0), $xml->sign->rectangle->position->x);
        $this->assertEquals($keyPath, $xml->sign->keystore->path);
        $this->assertEquals($type, $xml->sign->type);
        $this->assertEquals($local, $xml->sign->location);
        $this->assertEquals($reason, $xml->sign->reazon);
    }

    public function testGetNodeKeystoreNull()
    {
        $this->expectException(SerializeReportException::class);
        $sign = new Sign();
        $sign->createXmlNode(new \SimpleXMLElement("<root></root>"));
    }

    /**
     * @throws \Rebelo\Reports\Report\Sign\SignException
     */
    public function testFillApiRequest(): void
    {
        $data = [];

        $certificate = new Certificate();
        $certificate->setPassword("The cert pass");
        $certificate->setName("The cert name");

        $keystore = new Keystore();
        $keystore->setPassword("The password");
        $keystore->setCertificate($certificate);

        $rectangle = new Rectangle();
        $rectangle->setX(9);
        $rectangle->setY(29);
        $rectangle->setHeight(99);
        $rectangle->setWidth(199);
        $rectangle->setRotation(90);
        $rectangle->setVisible(true);

        $sign = new Sign();
        $sign->setReason("The reason");
        $sign->setContact("The contact");
        $sign->setLocation("The location");
        $sign->setLevel(Level::CERTIFIED_NO_CHANGES_ALLOWED());
        $sign->setType(Type::SELF_SIGNED());
        $sign->setKeystore($keystore);
        $sign->setRectangle($rectangle);
        $sign->fillApiRequest($data);

        $this->assertSame(
            $keystore->getPassword(),
            $data[Sign::API_N_SIGN][Keystore::API_P_KEY_STORE_PASS]
        );

        $this->assertSame(
            $certificate->getName(),
            $data[Sign::API_N_SIGN][Certificate::API_P_CERTIFICATE_NAME]
        );

        $this->assertSame(
            $certificate->getPassword(),
            $data[Sign::API_N_SIGN][Certificate::API_P_CERTIFICATE_PASS]
        );

        $this->assertSame($sign->getLevel()->get(), $data[Sign::API_N_SIGN][Sign::API_P_LEVEL]);
        $this->assertSame($sign->getType()->get(), $data[Sign::API_N_SIGN][Sign::API_P_CERTIFICATE_TYPE]);

        $this->assertSame(
            $rectangle->getX(),
            $data[Sign::API_N_SIGN][Rectangle::API_N_RECTANGLE][Rectangle::API_P_X]
        );

        $this->assertSame(
            $rectangle->getY(),
            $data[Sign::API_N_SIGN][Rectangle::API_N_RECTANGLE][Rectangle::API_P_Y]
        );

        $this->assertSame(
            $rectangle->getWidth(),
            $data[Sign::API_N_SIGN][Rectangle::API_N_RECTANGLE][Rectangle::API_P_WIDTH]
        );

        $this->assertSame(
            $rectangle->getHeight(),
            $data[Sign::API_N_SIGN][Rectangle::API_N_RECTANGLE][Rectangle::API_P_HEIGHT]
        );

        $this->assertSame(
            $rectangle->getRotation(),
            $data[Sign::API_N_SIGN][Rectangle::API_N_RECTANGLE][Rectangle::API_P_ROTATION]
        );
    }
}
