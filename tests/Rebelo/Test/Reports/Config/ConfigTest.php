<?php

namespace Rebelo\Test\Reports\Config;

/**
 * 
 *
 * @backupStaticAttributes enabled
 */
class ConfigTest
    extends \PHPUnit\Framework\TestCase
{

    public static string $ini_win   = __DIR__ . "/testconfigwin.properties";
    public static string $ini_linux = __DIR__ . "/testconfiglinux.properties";
    public static string $iniempty  = __DIR__ . "/testconfigempty.properties";

    /**
     * 
     */
    public function testNewInstance() : void
    {
        $this->expectException(\Error::class);
        new \Rebelo\Reports\Config\Config();/** @phpstan-ignore-line */
        $this->assertTrue(false, "Shoud not allow to create a instance");
    }

    /**
     * @covers Rebelo\Reports\Report\Config::getInstance
     */
    public function testGetInstance() : void
    {
        $this->assertTrue(
            \Rebelo\Reports\Config\Config::getInstance()
            instanceof
            \Rebelo\Reports\Config\Config
        );
    }

    /**
     * 
     */
    public function testNoIniFile() : void
    {
        $this->expectException(\Rebelo\Reports\Config\ConfigException::class);
        \Rebelo\Reports\Config\Config::$iniPath = "NO_FILE";
        \Rebelo\Reports\Config\Config::getInstance();
    }

   /**
    * 
    */
    public function testGetEmptyJavaPath() : void
    {
        $this->expectException(\Rebelo\Reports\Config\ConfigException::class);
        \Rebelo\Reports\Config\Config::$iniPath = static::$iniempty;
        \Rebelo\Reports\Config\Config::getInstance()->getJavaPath();
    }

    public function testGetEmptyXsharedClasses() : void
    {
        \Rebelo\Reports\Config\Config::$iniPath = static::$iniempty;
        $shared                                 = \Rebelo\Reports\Config\Config::getInstance()->getJavaXsharedClassesName();
        $this->assertNull($shared);
    }

  
    public function testGetEmptyTempDir() : void
    {
        $this->expectException(\Rebelo\Reports\Config\ConfigException::class);
        \Rebelo\Reports\Config\Config::$iniPath = static::$iniempty;
        \Rebelo\Reports\Config\Config::getInstance()->getTempDirectory();
    }

    public function setIni4Os() : void
    {
        if (\strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
        {
            \Rebelo\Reports\Config\Config::$iniPath = static::$ini_win;
        }
        else
        {
            \Rebelo\Reports\Config\Config::$iniPath = static::$ini_linux;
        }
    }

    public function testGetJavaPath() : void
    {
        $this->setIni4Os();
        $this->assertEquals(
            "java", \Rebelo\Reports\Config\Config::getInstance()->getJavaPath()
        );
        $path = "/path/to/java";
        $this->assertInstanceOf(
            "\Rebelo\Reports\Config\Config",
            \Rebelo\Reports\Config\Config::getInstance()->setJavaPath($path)
        );
        $this->assertEquals(
            $path, \Rebelo\Reports\Config\Config::getInstance()->getJavaPath()
        );
    }

    public function testJavaPathNull() : void
    {
        $this->expectException(\Rebelo\Reports\Config\ConfigException::class);
        $this->setIni4Os();
        \Rebelo\Reports\Config\Config::getInstance()->setJavaPath(null);/** @phpstan-ignore-line */
    }

  
    public function testJavaPathEmpty() : void
    {
        $this->expectException(\Rebelo\Reports\Config\ConfigException::class);
        $this->setIni4Os();
        \Rebelo\Reports\Config\Config::getInstance()->setJavaPath("");
    }

    public function testGetJarPath() : void
    {
        $this->setIni4Os();
        $this->assertEquals(
            "jar_path",
            \Rebelo\Reports\Config\Config::getInstance()->getJarPath()
        );
    }

    public function testGetXsharedClassesName() : void
    {
        $this->setIni4Os();
        $this->assertEquals(
            "reports4php",
            \Rebelo\Reports\Config\Config::getInstance()->getJavaXsharedClassesName()
        );
    }

    public function testGetXsharedClassesDir() : void
    {
        $this->setIni4Os();
        $this->assertEquals(
            "DirReports4php",
            \Rebelo\Reports\Config\Config::getInstance()->getJavaXsharedClassesDir()
        );
    }

    public function testGetTempDirectory() : void
    {
        $this->expectException(\Rebelo\Reports\Config\ConfigException::class);
        $this->setIni4Os();
        \Rebelo\Reports\Config\Config::getInstance()->getTempDirectory();
    }

    /**
     *
     */
    public function testSetTempDirectory() : void
    {
        $resource = __DIR__ . DIRECTORY_SEPARATOR . ".."
            . DIRECTORY_SEPARATOR . ".."
            . DIRECTORY_SEPARATOR . ".."
            . DIRECTORY_SEPARATOR . ".."
            . DIRECTORY_SEPARATOR . "Resources";
        $this->setIni4Os();
        \Rebelo\Reports\Config\Config::getInstance()->setTempDirectory($resource);
        $this->assertEquals(
            $resource,
            \Rebelo\Reports\Config\Config::getInstance()->getTempDirectory()
        );
    }

    public function testGetVerboseLevel() : void
    {
        $this->setIni4Os();
        $this->assertEquals(
            \Rebelo\Reports\Config\VerboseLevel::ALL,
            \Rebelo\Reports\Config\Config::getInstance()->getVerboseLevel()->get()
        );
    }

    public function testCleanLastSlash() : void
    {
        $this->assertEquals(
            "/tmp",
            \Rebelo\Reports\Config\Config::cleanLastSlash("/tmp/")
        );
        $this->assertEquals(
            "c:\tmp",
            \Rebelo\Reports\Config\Config::cleanLastSlash("c:\tmp\\")
        );
    }

}
