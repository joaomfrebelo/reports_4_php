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
//declare(strict_types=1);

namespace Rebelo\Reports\Report\Datasource;

/**
 * AServerHttp
 *
 * @author João Rebelo
 * @since 1.0.0
 */
abstract class AServerHttp
    extends AServer
{

    /**
     *
     * @param string $url The server url
     * @param \Rebelo\Reports\Report\Datasource\RequestType $type
     * @since 1.0.0
     */
    public function __construct($url = null, RequestType $type = null)
    {
        parent::__construct($type);

        $this->setUrl($url);
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    "Url setted to '%s' in construct",
                    $this->url === null
                        ? "null"
                    : $this->url
                )
            );
    }

    /**
     * Sets the server url
     *
     * The server URL
     *
     * @param string|null $url
     * @return self
     * @throws DatasourceException
     * @since 1.0.0
     */
    public function setUrl($url)
    {
        if ($url !== null)
        {
            if (strtolower(parse_url($url, PHP_URL_SCHEME)) !== "http")/** @phpstan-ignore-line */
            {
                $msg = sprintf(
                    __METHOD__ . " url must be http but '%s' was passed",
                    parse_url($url, PHP_URL_SCHEME)
                );
                \Logger::getLogger(\get_class($this))
                    ->error($msg);
                throw new DatasourceException($msg);
            }
        }

        $this->url = $url;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__ . " setted to '%s'",
                    $this->url === null
                        ? "null"
                    : $this->url
                )
            );
        return $this;
    }

}
