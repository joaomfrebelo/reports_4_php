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
 * @method static Action REPORT()
 * @method static Action CUT()
 * @method static Action CUT_AND_OPEN()
 * @method static Action CASH_DRAWER()
 * @author João Rebelo
 * @since  3.0.0
 */
class Action extends AEnum
{
    /**
     * Get report action
     * @since  3.0.0
     */
    const REPORT = "report";

    /**
     * Receipt printer cut  paper action
     * @since  3.0.0
     */
    const CUT = "cut";

    /**
     * Receipt printer cut  paper action and open cash drawer
     * @since  3.0.0
     */
    const CUT_AND_OPEN = "cutandopen";

    /**
     * Receipt printer open cash drawer
     * @since  3.0.0
     */

    const CASH_DRAWER = "cashdrawer";

    /**
     * @param string $value
     * @throws \Rebelo\Enum\EnumException
     * @throws \ReflectionException
     * @since  3.0.0
     */
    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    /**
     * Get the action value
     * @return string
     */
    public function get(): string
    {
        return parent::get();
    }

    /**
     * Get the http verb
     * @return string
     */
    public function getVerb(): string
    {
        return match ($this->get()) {
            self::REPORT => "POST",
            default => "GET"
        };
    }
}
