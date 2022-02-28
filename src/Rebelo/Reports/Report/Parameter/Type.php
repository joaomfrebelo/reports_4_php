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

namespace Rebelo\Reports\Report\Parameter;

use Rebelo\Enum\AEnum;

/**
 * Description of Type
 * @method static Type P_STRING()
 * @method static Type P_BOOL()
 * @method static Type P_BOOLEAN()
 * @method static Type P_DOUBLE()
 * @method static Type P_FLOAT()
 * @method static Type P_INTEGER()
 * @method static Type P_LONG()
 * @method static Type P_SHORT()
 * @method static Type P_BIGDECIMAL()
 * @method static Type P_DATE()
 * @method static Type P_TIME()
 * @method static Type P_SQL_TIME()
 * @method static Type P_SQL_DATE()
 * @method static Type P_TIMESTAMP()
 * @author João Rebelo
 */
class Type extends AEnum
{

    const P_STRING = "string";
    const P_BOOL = "bool";
    const P_BOOLEAN = "boolean";
    const P_DOUBLE = "double";
    const P_FLOAT = "float";
    const P_INTEGER = "integer";
    const P_LONG = "long";
    const P_SHORT = "short";
    const P_BIGDECIMAL = "bigdecimal";
    const P_DATE = "date";
    const P_TIME = "time";
    const P_SQL_TIME = "sqltime";
    const P_SQL_DATE = "sqldate";
    const P_TIMESTAMP = "timestamp";

    public function __construct($value)
    {
        parent::__construct($value);
    }
}
