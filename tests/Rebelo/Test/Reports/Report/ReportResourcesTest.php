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
use Rebelo\Reports\Config\Config;
use Rebelo\Reports\Report\ReportException;
use Rebelo\Reports\Report\ReportResources;

class ReportResourcesTest extends TestCase
{

    /**
     * @throws \Rebelo\Reports\Report\ReportException
     */
    public function testInstance(): void
    {
        $name     = "TheName";
        $resource = "The base64 string";

        $reportResource = new ReportResources($name, $resource);

        $this->assertSame($name, $reportResource->getName());
        $this->assertSame($resource, $reportResource->getResource());
    }

    public function testInstanceEmptyName(): void
    {
        $this->expectException(ReportException::class);
        new ReportResources(" ", "resource");
    }

    /**
     * @throws \Rebelo\Reports\Report\ReportException
     */
    public function testFillApiRequest(): void
    {
        $oldCacheResources = Config::getInstance()->getCacheResources();

        foreach ([true, false] as $cacheResources) {
            Config::getInstance()->setCacheResources($cacheResources);
            $name     = "TheName";
            $resource = "The base64 string";

            $reportResource = new ReportResources($name, $resource);

            $data                                   = [];
            $data[ReportResources::API_N_RESOURCES] = [];

            $reportResource->fillApiRequest($data);

            $result = $data[ReportResources::API_N_RESOURCES][0];

            $this->assertSame($name, $result[ReportResources::API_P_NAME]);
            $this->assertSame($resource, $result[ReportResources::API_P_RESOURCE]);
        }

        Config::getInstance()->setCacheResources($oldCacheResources);
    }

    /**
     * @throws \Rebelo\Reports\Config\ConfigException
     * @throws \Rebelo\Reports\Cache\CacheException
     */
    public function testFactoryFromFilePathWrongPath(): void
    {
        $oldCacheResources = Config::getInstance()->getCacheResources();
        Config::getInstance()->setCacheResources(false);

        $this->expectException(ReportException::class);
        ReportResources::factoryFromFilePath("name", "");

        Config::getInstance()->setCacheResources($oldCacheResources);
    }

    /**
     * @throws \Rebelo\Reports\Report\ReportException
     * @throws \Exception
     */
    public function testFactoryFromFilePath(): void
    {
        $oldCacheResources = Config::getInstance()->getCacheResources();

        foreach ([true, false] as $cacheResources) {
            Config::getInstance()->setCacheResources($cacheResources);

            $path = TEST_RESOURCES_DIR . DIRECTORY_SEPARATOR . "sakila.jasper";
            $name = "sakila.jasper";

            if (false === $contents = \file_get_contents($path)) {
                throw new \Exception(\sprintf("Fail load file '%s' for test", $path));
            }

            $reportResources = ReportResources::factoryFromFilePath($name, $path);

            $this->assertInstanceOf($reportResources::class, $reportResources);

            $this->assertSame($name, $reportResources->getName());
            $this->assertSame(\base64_encode($contents), $reportResources->getResource());
        }

        Config::getInstance()->setCacheResources($oldCacheResources);
    }
}
