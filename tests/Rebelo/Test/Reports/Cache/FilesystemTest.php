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

use PHPUnit\Framework\TestCase;
use Rebelo\Reports\Cache\Filesystem;

class FilesystemTest extends TestCase
{

    /**
     * @return void
     * @beforeClass
     * @throws \Rebelo\Reports\Config\ConfigException
     * @throws \Exception
     */
    public static function setUpBeforeClass(): void
    {
        Filesystem::clearCache();
    }

    /**
     * @return void
     * @throws \Rebelo\Reports\Cache\CacheException
     * @throws \Rebelo\Reports\Config\ConfigException
     * @throws \Exception
     */
    public function testCache(): void
    {
        $resourcePath = TEST_RESOURCES_DIR . DIRECTORY_SEPARATOR . "sakila.jasper";

        if (false === $resource = \file_get_contents($resourcePath)) {
            throw new \Exception(
                \sprintf("Fail perform test due fail to open file '%s'", $resourcePath)
            );
        }

        $cache = new Filesystem($resourcePath);

        $this->assertEquals(
            \base64_encode($resource),
            $cache->getResource()
        );

        $reCache = new Filesystem($resourcePath);

        $this->assertEquals(
            $cache->getResource(),
            $reCache->getResource()
        );
    }

    /**
     * @throws \Rebelo\Reports\Config\ConfigException
     * @throws \Rebelo\Reports\Cache\CacheException
     * @throws \Exception
     */
    public function testRemove(): void
    {
        $resourcePath = TEST_RESOURCES_DIR . DIRECTORY_SEPARATOR . "sakila.jasper";

        $cache = new Filesystem($resourcePath);
        $cache->getResource();

        $ref = new \ReflectionClass($cache);
        $pro = $ref->getProperty("cacheFilePath");
        $pro->setAccessible(true);
        $cachePath = $pro->getValue($cache);

        $this->assertTrue(\file_exists($cachePath));
        Filesystem::remove($resourcePath);
        $this->assertFalse(\file_exists($cachePath));
    }

    /**
     * @throws \Rebelo\Reports\Config\ConfigException
     * @throws \Rebelo\Reports\Cache\CacheException
     * @throws \Exception
     */
    public function testClearCache(): void
    {
        $resourcePath = \join(DIRECTORY_SEPARATOR, [
            TEST_RESOURCES_DIR,
            "TestSubReport",
            "eco_bike.png"
        ]);

        $cache = new Filesystem($resourcePath);
        $cache->getResource();

        $ref = new \ReflectionClass($cache);
        $pro = $ref->getProperty("cacheFilePath");
        $pro->setAccessible(true);
        $cachePath = $pro->getValue($cache);

        $this->assertTrue(\file_exists($cachePath));
        Filesystem::clearCache();
        $this->assertFalse(\file_exists($cachePath));
    }
}
