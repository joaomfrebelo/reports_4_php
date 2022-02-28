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

namespace Rebelo\Reports\Report\Api;

use Rebelo\Enum\AEnum;

/**
 * Response status
 * @method static Status OK()
 * @method static Status ERROR()
 * @author João Rebelo
 * @since 3.0.0
 */
class Status extends AEnum
{
    /**
     * Response OK
     * @since 3.0.0
     */
    const OK = "OK";

    /**
     * Response Error
     * @since 3.0.0
     */
    const ERROR = "ERROR";

    /**
     * @param string $value
     * @throws \Rebelo\Enum\EnumException
     */
    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    /**
     * Get as string
     * @return string
     */
    public function get(): string
    {
        return parent::get();
    }
}
