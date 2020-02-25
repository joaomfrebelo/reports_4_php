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

    public static $ini_win   = __DIR__ . "/testconfigwin.properties";
    public static $ini_linux = __DIR__ . "/testconfiglinux.properties";
    public static $iniempty  = __DIR__ . "/testconfigempty.properties";

    /**
     * @expectedException \Error
     */
    public function testNewInstance()
    {
        new \Rebelo\Reports\Config\Config();
        $this->assertTrue(false, "Shoud not allow to create a instance");
    }

    /**
     * @covers Rebelo\Reports\Report\Config::getInstance
     */
    public function testGetInstance()
    {
        $this->assertTrue(
            \Rebelo\Reports\Config\Config::getInstance()
            instanceof
            \Rebelo\Reports\Config\Config
        );
    }

    /**
     * @expectedException \Rebelo\Reports\Config\ConfigException
     */
    public function testNoIniFile()
    {
        \Rebelo\Reports\Config\Config::$iniPath = "NO_FILE";
        \Rebelo\Reports\Config\Config::getInstance();
    }

    /**
     * @expectedException \Rebelo\Reports\Config\ConfigException
     */
    public function testGetEmptyJavaPath()
    {
        \Rebelo\Reports\Config\Config::$iniPath = static::$iniempty;
        \Rebelo\Reports\Config\Config::getInstance()->getJavaPath();
    }

    public function testGetEmptyXsharedClasses()
    {
        \Rebelo\Reports\Config\Config::$iniPath = static::$iniempty;
        $shared                                 = \Rebelo\Reports\Config\Config::getInstance()->getJavaXsharedClassesName();
        $this->assertNull($shared);
    }

    /**
     * @expectedException \Rebelo\Reports\Config\ConfigException
     */
    public function testGetEmptyTempDir()
    {
        \Rebelo\Reports\Config\Config::$iniPath = static::$iniempty;
        \Rebelo\Reports\Config\Config::getInstance()->getTempDirectory();
    }

    public function setIni4Os()
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

    public function testGetJavaPath()
    {
        $this->setIni4Os();
        $this->assertEquals(
            "java", \Rebelo\Reports\Config\Config::getInstance()->getJavaPath()
        );
        $path = "/path/to/java";
        $this->assertInstanceOf("\Rebelo\Reports\Config\Config",
                                \Rebelo\Reports\Config\Config::getInstance()->setJavaPath($path));
        $this->assertEquals(
            $path, \Rebelo\Reports\Config\Config::getInstance()->getJavaPath()
        );
    }

    /**
     * @expectedException \Rebelo\Reports\Config\ConfigException
     */
    public function testJavaPathNull()
    {
        $this->setIni4Os();
        \Rebelo\Reports\Config\Config::getInstance()->setJavaPath(null);
    }

    /**
     * @expectedException \Rebelo\Reports\Config\ConfigException
     */
    public function testJavaPathEmpty()
    {
        $this->setIni4Os();
        \Rebelo\Reports\Config\Config::getInstance()->setJavaPath("");
    }

    public function testGetJarPath()
    {
        $this->setIni4Os();
        $this->assertEquals(
            "jar_path",
            \Rebelo\Reports\Config\Config::getInstance()->getJarPath()
        );
    }

    public function testGetXsharedClassesName()
    {
        $this->setIni4Os();
        $this->assertEquals(
            "reports4php",
            \Rebelo\Reports\Config\Config::getInstance()->getJavaXsharedClassesName()
        );
    }

    public function testGetXsharedClassesDir()
    {
        $this->setIni4Os();
        $this->assertEquals(
            "DirReports4php",
            \Rebelo\Reports\Config\Config::getInstance()->getJavaXsharedClassesDir()
        );
    }

    /**
     * @expectedException \Rebelo\Reports\Config\ConfigException
     */
    public function testGetTempDirectory()
    {
        $this->setIni4Os();
        \Rebelo\Reports\Config\Config::getInstance()->getTempDirectory();
    }

    /**
     *
     */
    public function testSetTempDirectory()
    {
        $resource = __DIR__ . DIRECTORY_SEPARATOR . ".."
            . DIRECTORY_SEPARATOR . ".."
            . DIRECTORY_SEPARATOR . ".."
            . DIRECTORY_SEPARATOR . ".."
            . DIRECTORY_SEPARATOR . "Resources";
        $this->setIni4Os();
        \Rebelo\Reports\Config\Config::getInstance()->setTempDirectory($resource);
        $this->assertEquals($resource,
                            \Rebelo\Reports\Config\Config::getInstance()->getTempDirectory());
    }

    public function testGetVerboseLevel()
    {
        $this->setIni4Os();
        $this->assertEquals(\Rebelo\Reports\Config\VerboseLevel::ALL,
                            \Rebelo\Reports\Config\Config::getInstance()->getVerboseLevel()->get());
    }

    public function testCleanLastSlash()
    {
        $this->assertEquals("/tmp",
                            \Rebelo\Reports\Config\Config::cleanLastSlash("/tmp/"));
        $this->assertEquals("c:\tmp",
                            \Rebelo\Reports\Config\Config::cleanLastSlash("c:\tmp\\"));
    }

}
