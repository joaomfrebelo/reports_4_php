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

namespace Rebelo\Test\Reports\Config;

use PHPUnit\Framework\TestCase;
use Rebelo\Reports\Config\Config;
use Rebelo\Reports\Config\ConfigException;
use Rebelo\Reports\Config\VerboseLevel;

/**
 *
 *
 * @backupStaticAttributes enabled
 */
class ConfigTest extends TestCase
{

    public static string $ini_win = __DIR__ . "/testconfigwin.properties";
    public static string $ini_linux = __DIR__ . "/testconfiglinux.properties";
    public static string $iniempty = __DIR__ . "/testconfigempty.properties";

    protected function setUp(): void
    {
        Config::$iniPath = static::$ini_win;
        static::initiateConfig();
    }

    protected function tearDown(): void
    {
        static::initiateConfig();
    }

    public static function initiateConfig(): void
    {
        $refClass        = new \ReflectionClass(Config::class);
        $refProp         = $refClass->getProperty("config");
        $refProp->setAccessible(true);
        $refProp->setValue(null);
    }

    /**
     * @covers \Rebelo\Reports\Config\Config::getInstance
     */
    public function testGetInstance()
    {
        $this->assertTrue(Config::getInstance() instanceof Config);
    }

    public function testNoIniFile()
    {
        $this->expectException(ConfigException::class);
        Config::$iniPath = "NO_FILE";
        Config::getInstance();
    }

    public function testGetEmptyJavaPath()
    {
        $this->expectException(ConfigException::class);
        Config::$iniPath = static::$iniempty;
        Config::getInstance()->getJavaPath();
    }

    public function testGetEmptyXsharedClasses()
    {
        Config::$iniPath = static::$iniempty;
        $shared          = Config::getInstance()->getJavaXsharedClassesName();
        $this->assertNull($shared);
    }

    /**
     * @throws \Rebelo\Reports\Config\ConfigException
     */
    public function testGetEmptyTempDirInConfigProperties()
    {
        Config::$iniPath = static::$iniempty;
        $this->assertSame(
            \sys_get_temp_dir(),
            Config::getInstance()->getTempDirectory()
        );
    }

    /**
     * @throws \Rebelo\Reports\Config\ConfigException
     */
    public function testSetGetEmptyTempDir()
    {
        $this->setIni4Os();
        Config::getInstance()->setTempDirectory("");
        $this->assertSame(
            \sys_get_temp_dir(),
            Config::getInstance()->getTempDirectory()
        );

        $this->setIni4Os();
    }

    public function setIni4Os()
    {
        if (\strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            Config::$iniPath = static::$ini_win;
        } else {
            Config::$iniPath = static::$ini_linux;
        }

        static::initiateConfig();
    }

    /**
     * @throws \Rebelo\Reports\Config\ConfigException
     */
    public function testGetJavaPath()
    {
        $this->setIni4Os();
        $this->assertEquals(
            "java",
            Config::getInstance()->getJavaPath()
        );
        $path = "/path/to/java";
        $this->assertInstanceOf(
            "\Rebelo\Reports\Config\Config",
            Config::getInstance()->setJavaPath($path)
        );
        $this->assertEquals(
            $path,
            Config::getInstance()->getJavaPath()
        );
    }

    public function testJavaPathEmpty()
    {
        $this->expectException(ConfigException::class);
        $this->setIni4Os();
        Config::getInstance()->setJavaPath("");
    }

    /**
     * @throws \Rebelo\Reports\Config\ConfigException
     */
    public function testGetJarPath()
    {
        $this->setIni4Os();
        $this->assertEquals(
            "jar_path",
            Config::getInstance()->getJarPath()
        );
    }

    public function testGetXsharedClassesName()
    {
        $this->setIni4Os();
        $this->assertEquals(
            "reports4php",
            Config::getInstance()->getJavaXsharedClassesName()
        );
    }

    public function testGetXsharedClassesDir()
    {
        $this->setIni4Os();
        $this->assertEquals(
            "DirReports4php",
            Config::getInstance()->getJavaXsharedClassesDir()
        );
    }

    public function testGetTempDirectory()
    {
        $this->expectException(ConfigException::class);
        $this->setIni4Os();
        Config::getInstance()->getTempDirectory();
    }

    /**
     *
     * @throws \Rebelo\Reports\Config\ConfigException
     */
    public function testSetTempDirectory()
    {
        $resource = __DIR__ . DIRECTORY_SEPARATOR . ".."
                    . DIRECTORY_SEPARATOR . ".."
                    . DIRECTORY_SEPARATOR . ".."
                    . DIRECTORY_SEPARATOR . ".."
                    . DIRECTORY_SEPARATOR . "Resources";
        $this->setIni4Os();
        Config::getInstance()->setTempDirectory($resource);
        $this->assertEquals(
            $resource,
            Config::getInstance()->getTempDirectory()
        );
    }

    /**
     * @throws \Rebelo\Enum\EnumException
     * @throws \Rebelo\Reports\Config\ConfigException
     */
    public function testGetVerboseLevel()
    {
        $this->setIni4Os();
        $this->assertEquals(
            VerboseLevel::ALL,
            Config::getInstance()->getVerboseLevel()->get()
        );
    }

    public function testCleanLastSlash()
    {
        $this->assertEquals(
            "/tmp",
            Config::cleanLastSlash("/tmp/")
        );
        $this->assertEquals(
            'c:\tmp',
            Config::cleanLastSlash('c:\tmp\\')
        );
    }

    /**
     * @throws \Rebelo\Reports\Config\ConfigException
     */
    public function testGetEndpoint(): void
    {
        $this->assertEquals(
            "http://localhost:4999",
            Config::getInstance()->getApiEndpoint()
        );
    }

    public function testNoEnpointConfigured()
    {
        $this->expectException(ConfigException::class);
        $config   = Config::getInstance();
        $refClass = new \ReflectionClass(Config::class);
        $refProp  = $refClass->getProperty("ini");
        $refProp->setAccessible(true);
        $refProp->setValue($config, []);
        $config->getApiEndpoint();
    }

    public function testCacheResources(): void
    {
        $this->assertTrue(
            Config::getInstance()->getCacheResources()
        );

        foreach ([false, true] as $bool) {
            $this->assertSame(
                $bool,
                Config::getInstance()->setCacheResources($bool)->getCacheResources()
            );
        }
    }
}
