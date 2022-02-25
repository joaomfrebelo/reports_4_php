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

namespace Rebelo\Reports\Report;

use Rebelo\Reports\Cache\Filesystem;
use Rebelo\Reports\Config\Config;

/**
 *
 * @author João Rebelo
 * @since  3.0.0
 */
class ReportResources
{

    /**
     * Api node name
     * @since 3.0.0
     */
    const API_N_RESOURCES = "reportResources";

    /**
     * Api property name
     * @since 3.0.0
     */
    const API_P_NAME = "name";

    /**
     * Api property name
     * @since 3.0.0
     */
    const API_P_RESOURCE = "resource";

    /**
     * @var \Logger
     * @since 3.0.0
     */
    protected \Logger $log;

    /**
     * @param string $name     The resource name loaded by the report (Ex: subreport.jasper)
     * @param string $resource The resource as base64 encoding string
     * @throws \Rebelo\Reports\Report\ReportException If name is empty
     * @since 3.0.0
     */
    public function __construct(protected string $name, protected string $resource)
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);
        $this->log->debug(\sprintf("Name set to '%s'", $this->name));
        if ("" === $this->name = \trim($name)) {
            $msg = "Report resource name can not be empty";
            $this->log->error($msg);
            throw new ReportException($msg);
        }
    }

    /**
     * The resource name,
     * is the name of the file that will be create and
     * loaded by the report (Ex: subreport.jasper)
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * The resource as base64 encoding string
     * @return string
     */
    public function getResource(): string
    {
        return $this->resource;
    }

    /**
     * @param string $name The name of the file that will be create and loaded by the report (Ex: subreport.jasper)
     * @param string $path The path of the resource file
     * @return \Rebelo\Reports\Report\ReportResources
     * @throws \Rebelo\Reports\Report\ReportException
     * @throws \Rebelo\Reports\Cache\CacheException
     * @throws \Rebelo\Reports\Config\ConfigException
     * @since 3.0.0
     */
    public static function factoryFromFilePath(string $name, string $path): ReportResources
    {
        if (Config::getInstance()->getCacheResources()) {
            $fileBase64 = (new Filesystem($path))->getResource();
        } else {
            if (false === \is_file($path) || false === $file = @\file_get_contents($path)) {
                $msg = \sprintf("Fail load file resource '%s'", $path);
                \Logger::getLogger(ReportResources::class)->debug(__METHOD__);
                throw new ReportException($msg);
            }
            $fileBase64 = \base64_encode($file);
        }

        return new ReportResources($name, $fileBase64);
    }

    /**
     * Fill the array that will be used to make the request to the Rest API
     * @param array $data
     * @return void
     * @since 3.0.0
     */
    public function fillApiRequest(array &$data): void
    {
        $data[static::API_N_RESOURCES][] = [
            static::API_P_NAME => $this->name,
            static::API_P_RESOURCE => $this->resource,
        ];
    }
}
