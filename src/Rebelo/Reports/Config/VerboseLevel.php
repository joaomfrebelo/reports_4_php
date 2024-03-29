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

namespace Rebelo\Reports\Config;

use Rebelo\Enum\AEnum;

/**
 * Verbose Level to pass as argument to jar Rebelo reports CLI
 * @method static VerboseLevel OFF()
 * @method static VerboseLevel FATAL()
 * @method static VerboseLevel ERROR()
 * @method static VerboseLevel WARN()
 * @method static VerboseLevel INFO()
 * @method static VerboseLevel DEBUG()
 * @method static VerboseLevel TRACE()
 * @method static VerboseLevel ALL()
 * @author João Rebelo
 */
class VerboseLevel extends AEnum
{

    const OFF   = "OFF";
    const FATAL = "FATAL";
    const ERROR = "ERROR";
    const WARN  = "WARN";
    const INFO  = "INFO";
    const DEBUG = "DEBUG";
    const TRACE = "TRACE";
    const ALL   = "ALL";

    /**
     * Verbose Level to pass as argument to jar Rebelo reports CLI
     * @param string $value
     * @throws \Rebelo\Enum\EnumException
     * @since 1.0.0
     */
    public function __construct(string $value)
    {
        parent::__construct($value);
    }
}
