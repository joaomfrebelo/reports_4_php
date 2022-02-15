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
class ExecReturnTest extends TestCase
{

    public function testSetGet()
    {
        $inst       = "\Rebelo\Reports\Report\ExecReturn";
        $code       = 0;
        $messages   = [
            "line 1",
            "line 2",
        ];
        $execReturn = new ExecReturn($code, $messages);
        $this->assertInstanceOf($inst, $execReturn);
        $this->assertEquals($code, $execReturn->getCode());
        $this->assertEquals($messages, $execReturn->getMessages());
        $this->assertEquals(join("; ", $messages), $execReturn->messagesToString());

        $code2 = 2;
        $this->assertInstanceOf($inst, $execReturn->setCode($code2));
        $this->assertEquals($code2, $execReturn->getCode());

        $msg = [
            "error output",
        ];
        $this->assertInstanceOf($inst, $execReturn->setMessages($msg));
        $this->assertEquals($msg, $execReturn->getMessages());
    }
}
