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

namespace Rebelo\Reports\Report\Sign;

use Rebelo\Enum\AEnum;

/**
 * Signature Level
 * @method static Level CERTIFIED_NO_CHANGES_ALLOWED()
 * @method static Level CERTIFIED_FORM_FILLING()
 * @method static Level CERTIFIED_FORM_FILLING_AND_ANNOTATIONS()
 * @author João Rebelo
 * @since 1.0.0
 */
class Level extends AEnum
{

    /**
     *
     * @since 1.0.0
     */
    const CERTIFIED_NO_CHANGES_ALLOWED = "CERTIFIED_NO_CHANGES_ALLOWED";

    /**
     *
     * @since 1.0.0
     */
    const CERTIFIED_FORM_FILLING = "CERTIFIED_FORM_FILLING";

    /**
     *
     * @since 1.0.0
     */
    const CERTIFIED_FORM_FILLING_AND_ANNOTATIONS = "CERTIFIED_FORM_FILLING_AND_ANNOTATIONS";

    /**
     *
     * @param String $level One of the constants of Level
     * @throws \Rebelo\Enum\EnumException
     * @throws \ReflectionException
     * @since 1.0.0
     */
    public function __construct(string $level)
    {
        parent::__construct($level);
    }
}
