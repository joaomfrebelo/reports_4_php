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
use Rebelo\Reports\Report\Sign\Rectangle;

/**
 * Class KeystoreTest
 *
 * @author João Rebelo
 */
class RectangleTest extends TestCase
{

    public function testSetGet(): void
    {
        $inst = "\Rebelo\Reports\Report\Sign\Rectangle";
        $rect = new Rectangle();
        $this->assertInstanceOf($inst, $rect);
        $this->assertNull($rect->getHeight());
        $this->assertNull($rect->getWidth());
        $this->assertNull($rect->getX());
        $this->assertNull($rect->getY());
        $this->assertFalse($rect->getVisible());
        $this->assertEquals(0, $rect->getRotation());

        $h   = 9;
        $w   = 79;
        $x   = 99;
        $y   = 999;
        $rot = 45;
        $vis = true;

        $this->assertInstanceOf($inst, $rect->setHeight($h));
        $this->assertEquals($h, $rect->getHeight());

        $this->assertInstanceOf($inst, $rect->setWidth($w));
        $this->assertEquals($w, $rect->getWidth());

        $this->assertInstanceOf($inst, $rect->setX($x));
        $this->assertEquals($x, $rect->getX());

        $this->assertInstanceOf($inst, $rect->setY($y));
        $this->assertEquals($y, $rect->getY());

        $this->assertInstanceOf($inst, $rect->setRotation($rot));
        $this->assertEquals($rot, $rect->getRotation());

        $rect->setVisible(true);
        $this->assertTrue($rect->getVisible());
        $rect->setVisible(false);
        $this->assertFalse($rect->getVisible());
        $this->assertInstanceOf($inst, $rect->setVisible($vis));
        $this->assertEquals($vis, $rect->getVisible());

        $node = new \SimpleXMLElement("<root></root>", LIBXML_NOCDATA);
        $rect->createXmlNode($node);
        if (false === $xml  = simplexml_load_string($node->asXML())) { /** @phpstan-ignore-line */
            $this->fail("Fail load xml string");
        }
        $this->assertEquals(strval($x), $xml->rectangle->position->x);
        $this->assertEquals(strval($y), $xml->rectangle->position->y);
        $this->assertEquals(strval($w), $xml->rectangle->position->width);
        $this->assertEquals(strval($h), $xml->rectangle->position->height);
        $this->assertEquals(strval($rot), $xml->rectangle->position->rotation);
        $this->assertEquals($vis, (string)$xml->rectangle->visible === "true");
    }

    public function testSetXZero(): void
    {
        $rect = new Rectangle();
        $rect->setX(0);
        $this->assertEquals(0, $rect->getX());
    }

    public function testSetXNegative(): void
    {
        $this->expectException(\Rebelo\Reports\Report\Sign\SignException::class);
        $rect = new Rectangle();
        $rect->setX(-1);
    }

    public function testSetYZero(): void
    {
        $rect = new Rectangle();
        $rect->setY(0);
        $this->assertEquals(0, $rect->getY());
    }

    public function testSetYNegative(): void
    {
        $this->expectException(\Rebelo\Reports\Report\Sign\SignException::class);
        $rect = new Rectangle();
        $rect->setY(-1);
    }

    public function testSetWidthZero(): void
    {
        $rect = new Rectangle();
        $rect->setWidth(0);
        $this->assertEquals(0, $rect->getWidth());
    }

    public function testSetWidthNegative(): void
    {
        $this->expectException(\Rebelo\Reports\Report\Sign\SignException::class);
        $rect = new Rectangle();
        $rect->setWidth(-1);
    }

    public function testSetHeightZero(): void
    {
        $rect = new Rectangle();
        $rect->setHeight(0);
        $this->assertEquals(0, $rect->getHeight());
    }

    public function testSetHeightNegative(): void
    {
        $this->expectException(\Rebelo\Reports\Report\Sign\SignException::class);
        $rect = new Rectangle();
        $rect->setHeight(-1);
    }

    public function testSetRotationtZero(): void
    {
        $rect = new Rectangle();
        $rect->setRotation(0);
        $this->assertEquals(0, $rect->getRotation());
    }

    public function testSetRotationNegative(): void
    {
        $this->expectException(\Rebelo\Reports\Report\Sign\SignException::class);
        $rect = new Rectangle();
        $rect->setRotation(-1);
    }

    public function testEmtyrecNode(): void
    {
        $rect = new Rectangle();

        $node = new \SimpleXMLElement("<root></root>", LIBXML_NOCDATA);
        $rect->createXmlNode($node);
        if (false === $xml  = simplexml_load_string($node->asXML())) { /** @phpstan-ignore-line */
            $this->fail("Fail load xml string");
        }
        $this->assertEquals(strval(0), $xml->rectangle->position->x);
        $this->assertEquals(strval(0), $xml->rectangle->position->y);
        $this->assertEquals(strval(0), $xml->rectangle->position->width);
        $this->assertEquals(strval(0), $xml->rectangle->position->height);
        $this->assertEquals(strval(0), $xml->rectangle->position->rotation);
    }
}