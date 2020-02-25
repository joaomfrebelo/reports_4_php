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
use Rebelo\Reports\Report\ExecReturn;

/**
 * Class ExecReturnTest
 *
 * @author João Rebelo
 */
class ExecReturnTest
    extends TestCase
{

    public function testSetGet()
    {
        $inst     = "\Rebelo\Reports\Report\ExecReturn";
        $code     = 0;
        $messages = array(
            "line 1",
            "line 2"
        );
        $exret    = new ExecReturn($code, $messages);
        $this->assertInstanceOf($inst, $exret);
        $this->assertEquals($code, $exret->getCode());
        $this->assertEquals($messages, $exret->getMessages());
        $this->assertEquals(join("; ", $messages), $exret->messagesToString());

        $code2 = 2;
        $this->assertInstanceOf($inst, $exret->setCode($code2));
        $this->assertEquals($code2, $exret->getCode());

        $msg = array(
            "error output"
        );
        $this->assertInstanceOf($inst, $exret->setMessages($msg));
        $this->assertEquals($msg, $exret->getMessages());
    }

    /**
     * @expectedException \Rebelo\Reports\Report\ExecException
     */
    public function setStringCodeConstruct()
    {
        new ExecReturn("", array());
    }

    /**
     * @expectedException \Rebelo\Reports\Report\ExecException
     */
    public function setNullCodeConstruct()
    {
        new ExecReturn(null, array());
    }

    /**
     * @expectedException \Rebelo\Reports\Report\ExecException
     */
    public function setEmptyConstruct()
    {
        new ExecReturn();
    }

    /**
     * @expectedException \Rebelo\Reports\Report\ExecException
     */
    public function setWrongMessageConstruct()
    {
        new ExecReturn(0, "");
    }

}
