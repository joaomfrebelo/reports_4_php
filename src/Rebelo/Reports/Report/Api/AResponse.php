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

/**
 * @since 3.0.0
 */
abstract class AResponse
{

    /**
     * @var \Logger
     * @since 3.0.0
     */
    protected \Logger $log;

    /**
     * Response
     * @param \Rebelo\Reports\Report\Api\Status $status The response status
     * @param string                            $message The response message
     * @param string                            $duration The server reports export duration
     */
    public function __construct(
        protected Status $status,
        protected string $message,
        protected string $duration
    ) {
        $this-> log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);
        $this->log->debug(
            \sprintf("Status: '%s'", $this->status->get())
        );
        $this->log->debug(
            \sprintf("Message: '%s'", $this->message)
        );
        $this->log->debug(
            \sprintf("Duration: '%s'", $this->duration)
        );
    }

    /**
     * Get the response status
     * @return \Rebelo\Reports\Report\Api\Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * Get the response message
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Get the server report export duration
     * @return string
     */
    public function getDuration(): string
    {
        return $this->duration;
    }
}
