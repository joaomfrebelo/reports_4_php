<?php

declare(strict_types = 1);

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

    public static string $ini_win   = __DIR__ . "/testconfigwin.properties";
    public static string $ini_linux = __DIR__ . "/testconfiglinux.properties";
    public static string $iniempty  = __DIR__ . "/testconfigempty.properties";

    protected function setUp(): void
    {
        Config::$iniPath = static::$ini_win;
        $refClass = new \ReflectionClass(Config::class);
        $refProp = $refClass->getProperty("config");
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
        $shared                                 = Config::getInstance()->getJavaXsharedClassesName();
        $this->assertNull($shared);
    }

    public function testGetEmptyTempDir()
    {
        $this->expectException(ConfigException::class);
        Config::$iniPath = static::$iniempty;
        Config::getInstance()->getTempDirectory();
    }


    public function setIni4Os()
    {
        if (\strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            Config::$iniPath = static::$ini_win;
        } else {
            Config::$iniPath = static::$ini_linux;
        }
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
            "c:\tmp",
            Config::cleanLastSlash("c:\tmp\\")
        );
    }
}
