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

namespace Rebelo\Test\Reports\Report\Parameter;

use PHPUnit\Framework\TestCase;
use Rebelo\Reports\Report\Parameter\Parameter;
use Rebelo\Reports\Report\Parameter\Type;

/**
 * Class ParameterTest
 *
 * @author João Rebelo
 */
class ParameterTest
    extends TestCase
{

    protected function setUp()
    {

    }

    protected function tearDown()
    {

    }

    public function testSetGetAndXmlBigDecimal()
    {
        $typeBigDec  = new Type(Type::P_BIGDECIMAL);
        $BigDecName  = "BIG_DECIMAL";
        $BigDecValue = 0.9;
        $BigDecParam = new Parameter($typeBigDec, $BigDecName, $BigDecValue);
        $this->assertEquals($typeBigDec->get(), $BigDecParam->getType()->get());
        $this->assertEquals($BigDecName, $BigDecParam->getName());
        $this->assertEquals(strval($BigDecValue), $BigDecParam->getValue());

        $bigDecNode = new \SimpleXMLElement("<parameters></parameters>",
                                            LIBXML_NOCDATA);
        $BigDecParam->createXmlNode($bigDecNode);
        $xmlBigDec  = simplexml_load_string($bigDecNode->asXML());
        $this->assertEquals($typeBigDec->get(), $xmlBigDec->parameter->type);
        $this->assertEquals($BigDecName, $xmlBigDec->parameter->name);
        $this->assertEquals(strval($BigDecValue), $xmlBigDec->parameter->value);
    }

    public function testSetGetAndXmlBool()
    {
        // Type boolean true
        $typebooleanTrue  = new Type(Type::P_BOOLEAN);
        $booleanTrueName  = "booleanTrue";
        $booleanTrueValue = true;
        $booleanTrueParam = new Parameter($typebooleanTrue, $booleanTrueName,
                                          $booleanTrueValue);
        $this->assertEquals($typebooleanTrue->get(),
                            $booleanTrueParam->getType()->get());
        $this->assertEquals($booleanTrueName, $booleanTrueParam->getName());
        $this->assertEquals("true", $booleanTrueParam->getValue());

        $booleanTrueNode = new \SimpleXMLElement("<parameters></parameters>",
                                                 LIBXML_NOCDATA);
        $booleanTrueParam->createXmlNode($booleanTrueNode);
        $xmlBooleanTrue  = simplexml_load_string($booleanTrueNode->asXML());
        $this->assertEquals($typebooleanTrue->get(),
                            $xmlBooleanTrue->parameter->type);
        $this->assertEquals($booleanTrueName, $xmlBooleanTrue->parameter->name);
        $this->assertEquals("true", $xmlBooleanTrue->parameter->value);

        // type boolean false
        $typebooleanFalse  = new Type(Type::P_BOOLEAN);
        $booleanFalseName  = "booleanFalse";
        $booleanFalseValue = false;
        $booleanFalseParam = new Parameter($typebooleanFalse, $booleanFalseName,
                                           $booleanFalseValue);
        $this->assertEquals($typebooleanFalse->get(),
                            $booleanFalseParam->getType()->get());
        $this->assertEquals($booleanFalseName, $booleanFalseParam->getName());
        $this->assertEquals("false", $booleanFalseParam->getValue());

        $booleanFalseNode = new \SimpleXMLElement("<parameters></parameters>",
                                                  LIBXML_NOCDATA);
        $booleanFalseParam->createXmlNode($booleanFalseNode);
        $xmlBooleanFalse  = simplexml_load_string($booleanFalseNode->asXML());
        $this->assertEquals($typebooleanFalse->get(),
                            $xmlBooleanFalse->parameter->type);
        $this->assertEquals($booleanFalseName, $xmlBooleanFalse->parameter->name);
        $this->assertEquals("false", $xmlBooleanFalse->parameter->value);

        // Type bool false
        $typeboolFalse  = new Type(Type::P_BOOL);
        $boolFalseName  = "boolFalse";
        $boolFalseValue = false;
        $boolFalseParam = new Parameter($typeboolFalse, $boolFalseName,
                                        $boolFalseValue);
        $this->assertEquals($typeboolFalse->get(),
                            $boolFalseParam->getType()->get());
        $this->assertEquals($boolFalseName, $boolFalseParam->getName());
        $this->assertEquals("false", $boolFalseParam->getValue());

        $boolFalseNode = new \SimpleXMLElement("<parameters></parameters>",
                                               LIBXML_NOCDATA);
        $boolFalseParam->createXmlNode($boolFalseNode);
        $xmlboolFalse  = simplexml_load_string($boolFalseNode->asXML());
        $this->assertEquals($typeboolFalse->get(),
                            $xmlboolFalse->parameter->type);
        $this->assertEquals($boolFalseName, $xmlboolFalse->parameter->name);
        $this->assertEquals("false", $xmlboolFalse->parameter->value);

        // bool true
        $typeboolTrue  = new Type(Type::P_BOOL);
        $boolTrueName  = "boolTrue";
        $boolTrueValue = true;
        $boolTrueParam = new Parameter($typeboolTrue, $boolTrueName,
                                       $boolTrueValue);
        $this->assertEquals($typeboolTrue->get(),
                            $boolTrueParam->getType()->get());
        $this->assertEquals($boolTrueName, $boolTrueParam->getName());
        $this->assertEquals("true", $boolTrueParam->getValue());

        $boolTrueNode = new \SimpleXMLElement("<parameters></parameters>",
                                              LIBXML_NOCDATA);
        $boolTrueParam->createXmlNode($boolTrueNode);
        $xmlboolTrue  = simplexml_load_string($boolTrueNode->asXML());
        $this->assertEquals($typeboolTrue->get(), $xmlboolTrue->parameter->type);
        $this->assertEquals($boolTrueName, $xmlboolTrue->parameter->name);
        $this->assertEquals("true", $xmlboolTrue->parameter->value);
    }

    public function testSetGetAndXmlString()
    {
        $typestr  = new Type(Type::P_STRING);
        $strName  = "STRING";
        $strValue = "str";
        $strParam = new Parameter($typestr, $strName, $strValue);
        $this->assertEquals($typestr->get(), $strParam->getType()->get());
        $this->assertEquals($strName, $strParam->getName());
        $this->assertEquals(strval($strValue), $strParam->getValue());

        $strNameNode = new \SimpleXMLElement("<parameters></parameters>",
                                             LIBXML_NOCDATA);
        $strParam->createXmlNode($strNameNode);
        $xmlStr      = simplexml_load_string($strNameNode->asXML());
        $this->assertEquals($typestr->get(), $xmlStr->parameter->type);
        $this->assertEquals($strName, $xmlStr->parameter->name);
        $this->assertEquals($strValue, $xmlStr->parameter->value);
    }

    public function testSetGetAndXmlDate()
    {
        $typedate   = new Type(Type::P_DATE);
        $dateName   = "date";
        $dateValue  = "1969-10-05";
        $dateFormat = "yyyy-MM-dd";
        $dateParam  = new Parameter($typedate, $dateName, $dateValue,
                                    $dateFormat);
        $this->assertEquals($typedate->get(), $dateParam->getType()->get());
        $this->assertEquals($dateName, $dateParam->getName());
        $this->assertEquals($dateValue, $dateParam->getValue());
        $this->assertEquals($dateFormat, $dateParam->getFormat());

        $dateNameNode = new \SimpleXMLElement("<parameters></parameters>",
                                              LIBXML_NOCDATA);
        $dateParam->createXmlNode($dateNameNode);
        $xmlStr       = simplexml_load_string($dateNameNode->asXML());
        $this->assertEquals($typedate->get(), $xmlStr->parameter->type);
        $this->assertEquals($dateName, $xmlStr->parameter->name);
        $this->assertEquals($dateValue, $xmlStr->parameter->value);
        $this->assertEquals($dateFormat, $xmlStr->parameter->value[0]["format"]);
    }

    /**
     * @expectedException \Rebelo\Reports\Report\Parameter\ParameterException
     */
    public function testSetGetAndXmlDateNoFormat()
    {
        $typedate  = new Type(Type::P_DATE);
        $dateName  = "date";
        $dateValue = "1969-10-05";
        new Parameter($typedate, $dateName, $dateValue);
    }

    public function testSetGetAndXmlFloat()
    {
        $type  = new Type(Type::P_FLOAT);
        $name  = "float name";
        $value = floatval(0.999);
        $param = new Parameter($type, $name, $value);
        $this->assertEquals($type->get(), $param->getType()->get());
        $this->assertEquals($name, $param->getName());
        $this->assertEquals(strval($value), $param->getValue());

        $dateNameNode = new \SimpleXMLElement("<parameters></parameters>",
                                              LIBXML_NOCDATA);
        $param->createXmlNode($dateNameNode);
        $xmlStr       = simplexml_load_string($dateNameNode->asXML());
        $this->assertEquals($type->get(), $xmlStr->parameter->type);
        $this->assertEquals($name, $xmlStr->parameter->name);
        $this->assertEquals(strval($value), $xmlStr->parameter->value);
    }

    public function testSetGetAndXmlDouble()
    {
        $type  = new Type(Type::P_DOUBLE);
        $name  = "double name";
        $value = doubleval(0.999);
        $param = new Parameter($type, $name, $value);
        $this->assertEquals($type->get(), $param->getType()->get());
        $this->assertEquals($name, $param->getName());
        $this->assertEquals(strval($value), $param->getValue());

        $dateNameNode = new \SimpleXMLElement("<parameters></parameters>",
                                              LIBXML_NOCDATA);
        $param->createXmlNode($dateNameNode);
        $xmlStr       = simplexml_load_string($dateNameNode->asXML());
        $this->assertEquals($type->get(), $xmlStr->parameter->type);
        $this->assertEquals($name, $xmlStr->parameter->name);
        $this->assertEquals(strval($value), $xmlStr->parameter->value);
    }

    public function testSetGetAndXmlInteger()
    {
        $type  = new Type(Type::P_INTEGER);
        $name  = "integer name";
        $value = 999;
        $param = new Parameter($type, $name, $value);
        $this->assertEquals($type->get(), $param->getType()->get());
        $this->assertEquals($name, $param->getName());
        $this->assertEquals(strval($value), $param->getValue());

        $dateNameNode = new \SimpleXMLElement("<parameters></parameters>",
                                              LIBXML_NOCDATA);
        $param->createXmlNode($dateNameNode);
        $xmlStr       = simplexml_load_string($dateNameNode->asXML());
        $this->assertEquals($type->get(), $xmlStr->parameter->type);
        $this->assertEquals($name, $xmlStr->parameter->name);
        $this->assertEquals(strval($value), $xmlStr->parameter->value);
    }

    public function testSetGetAndXmlLong()
    {
        $type  = new Type(Type::P_LONG);
        $name  = "long name";
        $value = 999;
        $param = new Parameter($type, $name, $value);
        $this->assertEquals($type->get(), $param->getType()->get());
        $this->assertEquals($name, $param->getName());
        $this->assertEquals(strval($value), $param->getValue());

        $dateNameNode = new \SimpleXMLElement("<parameters></parameters>",
                                              LIBXML_NOCDATA);
        $param->createXmlNode($dateNameNode);
        $xmlStr       = simplexml_load_string($dateNameNode->asXML());
        $this->assertEquals($type->get(), $xmlStr->parameter->type);
        $this->assertEquals($name, $xmlStr->parameter->name);
        $this->assertEquals(strval($value), $xmlStr->parameter->value);
    }

    public function testSetGetAndXmlShort()
    {
        $type  = new Type(Type::P_SHORT);
        $name  = "short name";
        $value = 9;
        $param = new Parameter($type, $name, $value);
        $this->assertEquals($type->get(), $param->getType()->get());
        $this->assertEquals($name, $param->getName());
        $this->assertEquals(strval($value), $param->getValue());

        $dateNameNode = new \SimpleXMLElement("<parameters></parameters>",
                                              LIBXML_NOCDATA);
        $param->createXmlNode($dateNameNode);
        $xmlStr       = simplexml_load_string($dateNameNode->asXML());
        $this->assertEquals($type->get(), $xmlStr->parameter->type);
        $this->assertEquals($name, $xmlStr->parameter->name);
        $this->assertEquals(strval($value), $xmlStr->parameter->value);
    }

    public function testSetGetAndXmlSqlDate()
    {
        $type   = new Type(Type::P_SQL_DATE);
        $name   = "sql date name";
        $value  = "1969-10-05";
        $format = "yyyy-MM-dd";
        $param  = new Parameter($type, $name, $value, $format);
        $this->assertEquals($type->get(), $param->getType()->get());
        $this->assertEquals($name, $param->getName());
        $this->assertEquals(strval($value), $param->getValue());
        $this->assertEquals($format, $param->getFormat());

        $dateNameNode = new \SimpleXMLElement("<parameters></parameters>",
                                              LIBXML_NOCDATA);
        $param->createXmlNode($dateNameNode);
        $xmlStr       = simplexml_load_string($dateNameNode->asXML());
        $this->assertEquals($type->get(), $xmlStr->parameter->type);
        $this->assertEquals($name, $xmlStr->parameter->name);
        $this->assertEquals(strval($value), $xmlStr->parameter->value);
        $this->assertEquals($format, $xmlStr->parameter->value[0]["format"]);
    }

    /**
     * @expectedException \Rebelo\Reports\Report\Parameter\ParameterException
     */
    public function testSetGetAndXmlSqlDateNoFormat()
    {
        $type  = new Type(Type::P_SQL_DATE);
        $name  = "sql date name";
        $value = "1969-10-05";
        new Parameter($type, $name, $value);
    }

    public function testSetGetAndXmlSqlTime()
    {
        $type  = new Type(Type::P_SQL_TIME);
        $name  = "sql time name";
        $value = 123456789;
        $param = new Parameter($type, $name, $value);
        $this->assertEquals($type->get(), $param->getType()->get());
        $this->assertEquals($name, $param->getName());
        $this->assertEquals(strval($value), $param->getValue());

        $dateNameNode = new \SimpleXMLElement("<parameters></parameters>",
                                              LIBXML_NOCDATA);
        $param->createXmlNode($dateNameNode);
        $xmlStr       = simplexml_load_string($dateNameNode->asXML());
        $this->assertEquals($type->get(), $xmlStr->parameter->type);
        $this->assertEquals($name, $xmlStr->parameter->name);
        $this->assertEquals(strval($value), $xmlStr->parameter->value);
    }

    public function testSetGetAndXmlTime()
    {
        $type  = new Type(Type::P_TIME);
        $name  = "time name";
        $value = 123456789;
        $param = new Parameter($type, $name, $value);
        $this->assertEquals($type->get(), $param->getType()->get());
        $this->assertEquals($name, $param->getName());
        $this->assertEquals(strval($value), $param->getValue());

        $dateNameNode = new \SimpleXMLElement("<parameters></parameters>",
                                              LIBXML_NOCDATA);
        $param->createXmlNode($dateNameNode);
        $xmlStr       = simplexml_load_string($dateNameNode->asXML());
        $this->assertEquals($type->get(), $xmlStr->parameter->type);
        $this->assertEquals($name, $xmlStr->parameter->name);
        $this->assertEquals(strval($value), $xmlStr->parameter->value);
    }

    public function testSetGetAndXmlTimestamp()
    {
        $type  = new Type(Type::P_TIMESTAMP);
        $name  = "timestamp name";
        $value = 123456789;
        $param = new Parameter($type, $name, $value);
        $this->assertEquals($type->get(), $param->getType()->get());
        $this->assertEquals($name, $param->getName());
        $this->assertEquals(strval($value), $param->getValue());

        $dateNameNode = new \SimpleXMLElement("<parameters></parameters>",
                                              LIBXML_NOCDATA);
        $param->createXmlNode($dateNameNode);
        $xmlStr       = simplexml_load_string($dateNameNode->asXML());
        $this->assertEquals($type->get(), $xmlStr->parameter->type);
        $this->assertEquals($name, $xmlStr->parameter->name);
        $this->assertEquals(strval($value), $xmlStr->parameter->value);
    }

    /**
     * @expectedException \Rebelo\Reports\Report\Parameter\ParameterException
     */
    public function testEmptyName()
    {
        $type = new Type(Type::P_STRING);
        new Parameter($type, "", "value");
    }

    /**
     * @expectedException \Rebelo\Reports\Report\Parameter\ParameterException
     */
    public function testNullName()
    {
        $type = new Type(Type::P_STRING);
        new Parameter($type, null, "value");
    }

}
