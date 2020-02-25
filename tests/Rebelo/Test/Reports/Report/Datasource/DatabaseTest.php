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

namespace Rebelo\Test\Reports\Report\Datasource;

use PHPUnit\Framework\TestCase;

/**
 * Class AServer
 *
 * @author João Rebelo
 */
class DatabaseTest
    extends TestCase
{

    protected function setUp()
    {

    }

    protected function tearDown()
    {

    }

    public function testSetGetConnectionString()
    {
        $inst = "\Rebelo\Reports\Report\Datasource\Database";
        $db   = new \Rebelo\Reports\Report\Datasource\Database();
        $this->assertInstanceOf($inst, $db);
        $this->assertNull($db->getConnectionString());
        $this->assertTrue($db->setConnectionString("conection") instanceof
            \Rebelo\Reports\Report\Datasource\Database);
        $this->assertEquals("conection", $db->getConnectionString());
        $db->setConnectionString(null);
        $this->assertNull($db->getConnectionString());
    }

    public function testSetDriver()
    {
        $db = new \Rebelo\Reports\Report\Datasource\Database();
        $this->assertNull($db->getDriver());
        $this->assertTrue($db->setDriver("driver") instanceof
            \Rebelo\Reports\Report\Datasource\Database);
        $this->assertEquals("driver", $db->getDriver());
    }

    public function testSetDriverNull()
    {
        $this->expectException("\Rebelo\Reports\Report\Datasource\DatasourceException");
        $db = new \Rebelo\Reports\Report\Datasource\Database();
        $db->setDriver(null);
    }

    public function testSetDriverEmptyString()
    {
        $this->expectException("\Rebelo\Reports\Report\Datasource\DatasourceException");
        $db = new \Rebelo\Reports\Report\Datasource\Database();
        $db->setDriver(null);
    }

    public function testSetUser()
    {
        $db = new \Rebelo\Reports\Report\Datasource\Database();
        $this->assertNull($db->getUser());
        $this->assertTrue($db->setUser("user") instanceof
            \Rebelo\Reports\Report\Datasource\Database);
        $this->assertEquals("user", $db->getUser());
        $db->setUser(null);
        $this->assertNull($db->getUser());
    }

    public function testSetPassword()
    {
        $db = new \Rebelo\Reports\Report\Datasource\Database();
        $this->assertNull($db->getPassword());
        $this->assertTrue($db->setPassword("password") instanceof
            \Rebelo\Reports\Report\Datasource\Database);
        $this->assertEquals("password", $db->getPassword());
        $db->setPassword(null);
        $this->assertNull($db->getPassword());
    }

    public function testCreateXmlNode()
    {
        $conStr = "connstr";
        $driver = "MySql";
        $pwd    = "pwd";
        $user   = "nobody";

        $db = new \Rebelo\Reports\Report\Datasource\Database();
        $db->setConnectionString($conStr)
            ->setDriver($driver)
            ->setPassword($pwd)
            ->setUser($user);

        $node = new \SimpleXMLElement("<root></root>", LIBXML_NOCDATA);
        $db->createXmlNode($node);

        $xml = simplexml_load_string($node->asXML());
        $this->assertEquals($conStr, $xml->database->connectionString);
        $this->assertEquals($driver, $xml->database->driver);
        $this->assertEquals($pwd, $xml->database->password);
        $this->assertEquals($user, $xml->database->user);
    }

}
