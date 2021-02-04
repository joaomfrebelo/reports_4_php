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

namespace Rebelo\Reports\Config;

/**
 * Description of Config
 *
 * @author João Rebelo
 */
class Config
{

    /**
     *
     * The ini properties file path
     *
     * @var string
     * @since 1.0.0
     */
    public static $iniPath = __DIR__ . "/config.properties";

    /**
     * The java block name in the ini file
     * @since 1.0.0
     */
    const BLOCK_JAVA = "java";

    /**
     * The java path key inside the java block
     * @since 1.0.0
     */
    const KEY_JAVA_PATH = "jvm_path";

    /**
     * The jar (rebelo_cli) path key inside the java block
     * @since 1.0.0
     */
    const KEY_JAR_PATH = "jar_path";

    /**
     * the javaXshareclasses key inside the java block
     * @since 1.0.0
     */
    const KEY_X_SHARECLASSE_NAME = "XshareclassesName";

    /**
     * the javaXshareclasses key inside the java block
     * @since 1.0.0
     */
    const KEY_X_SHARECLASSE_DIR = "XshareclassesDir";

    /**
     * The system block inside the ini file
     * @since 1.0.0
     */
    const BLOCK_SYSTEM = "system";

    /**
     * The temp dir key name inside the system block
     * @since 1.0.0
     */
    const KEY_TMP = "tmp";

    /**
     * The verbose level key name inside the java block
     * @since 1.0.0
     */
    const KEY_VERBOSE = "verbose";

    /**
     *
     * Self instance
     *
     * Config|null
     * @since 1.0.0
     */
    protected static ?Config $config = null;

    /**
     *
     * The values parse from the file config properties
     *
     * @var array
     * @since 1.0.0
     */
    protected $ini = array();

    /**
     * Stores if log4php was already configured
     * @var boolean
     */
    protected static $isSettedLogConfig = false;

    /**
     *
     * @throws ConfigException
     * @since 1.0.0
     */
    protected function __construct()
    {
        \Rebelo\Reports\Config\Config::configLog4Php();
        \Logger::getLogger(__CLASS__)->debug(
            sprintf(
                "ini file is going to be setted to '%s'", static::$iniPath
            )
        );

        if (\is_file(static::$iniPath) === false || \is_readable(static::$iniPath) === false)
        {
            throw new ConfigException(
                sprintf(
                    "ini file '%s' is not a file or is not readable",
                    static::$iniPath
                )
            );
        }

        $ini = parse_ini_file(static::$iniPath, true);
        if ($ini === false)
        {
            throw new ConfigException(
                sprintf(
                    "Error parsing ini file '%s'", static::$iniPath
                )
            );
        }
        $this->ini = $ini;
    }

    /**
     *
     * Get the instance of config
     *
     * @return Config
     * @since 1.0.0
     */
    public static function getInstance()
    {
        if (static::$config !== null)
        {
            return static::$config;
        }
        static::$config = new Config();
        return static::$config;
    }

    /**
     *
     * Get the java jvm path
     *
     * @return string
     * @throws ConfigException
     * @since 1.0.0
     */
    public function getJavaPath()
    {
        if (\array_key_exists(static::BLOCK_JAVA, $this->ini))
        {
            if (\array_key_exists(
                static::KEY_JAVA_PATH,
                $this->ini[static::BLOCK_JAVA]
            )
            )
            {
                $path = $this->ini[static::BLOCK_JAVA][static::KEY_JAVA_PATH];
                if (empty($path) === false)
                {
                    return $path;
                }
            }
        }
        throw new ConfigException("java path is not defined in the ini file");
    }

    /**
     * Set the Java Virtual Machine
     * @param string $path
     * @return $this
     * @throws ConfigException
     * @since 1.0.0
     */
    public function setJavaPath($path)
    {
        if (!\is_string($path) || \trim($path) === "")
        {
            $msg = "Java Virtual Machine PAth must be a non empty string";
            \Logger::getLogger(\get_class($this))
                ->error(sprintf(__METHOD__ . " '%s'", $msg));
            throw new ConfigException($msg);
        }
        $this->ini[static::BLOCK_JAVA][static::KEY_JAVA_PATH] = $path;
        \Logger::getLogger(\get_class($this))
            ->debug(
                sprintf(
                    __METHOD__ . " setted to '%s'",
                    $this->ini[static::BLOCK_JAVA][static::KEY_JAVA_PATH]
                )
            );
        return $this;
    }

    /**
     *
     * Get the java jar (rebelo_cli) path
     *
     * @return string
     * @throws ConfigException
     * @since 1.0.0
     */
    public function getJarPath()
    {
        if (\array_key_exists(static::BLOCK_JAVA, $this->ini))
        {
            if (\array_key_exists(
                static::KEY_JAR_PATH,
                $this->ini[static::BLOCK_JAVA]
            )
            )
            {
                $path = $this->ini[static::BLOCK_JAVA][static::KEY_JAR_PATH];
                if (empty($path) === false)
                {
                    return $path;
                }
            }
        }
        throw new ConfigException("jar (rebeloreports_cli path is not defined in the ini file");
    }

    /**
     *
     * Get the java Xshareclasses name
     *
     * @return string|null the Xshareclasses name or null if not configurated
     * @since 1.0.0
     *      */
    public function getJavaXsharedClassesName()
    {
        if (\array_key_exists(static::BLOCK_JAVA, $this->ini))
        {
            if (\array_key_exists(
                static::KEY_X_SHARECLASSE_NAME,
                $this->ini[static::BLOCK_JAVA]
            ))
            {
                if (empty($this->ini[static::BLOCK_JAVA][static::KEY_X_SHARECLASSE_NAME]) === false)
                {
                    return $this->ini[static::BLOCK_JAVA][static::KEY_X_SHARECLASSE_NAME];
                }
            }
        }
        return null;
    }

    /**
     *
     * Get the java Xshareclasses dir cache
     *
     * @return string|null the Xshareclasses dir or null if not configurated
     * @since 1.0.0
     */
    public function getJavaXsharedClassesDir()
    {
        if (\array_key_exists(static::BLOCK_JAVA, $this->ini))
        {
            if (\array_key_exists(
                static::KEY_X_SHARECLASSE_DIR,
                $this->ini[static::BLOCK_JAVA]
            ))
            {
                if (empty($this->ini[static::BLOCK_JAVA][static::KEY_X_SHARECLASSE_DIR]) === false)
                {
                    return static::cleanLastSlash(
                        $this->ini[static::BLOCK_JAVA][static::KEY_X_SHARECLASSE_DIR]
                    );
                }
            }
        }
        return null;
    }

    /**
     *
     * Get Verbose Level to pass as argument to jar Rebelo reports CLI
     *
     * @return \Rebelo\Reports\Config\VerboseLevel
     * @throws ConfigException
     * @since 1.0.0
     */
    public function getVerboseLevel()
    {
        if (\array_key_exists(static::BLOCK_JAVA, $this->ini))
        {
            if (\array_key_exists(
                static::KEY_VERBOSE,
                $this->ini[static::BLOCK_JAVA]
            )
            )
            {
                $path = $this->ini[static::BLOCK_JAVA][static::KEY_VERBOSE];
                if (empty($path))
                {
                    return new VerboseLevel(VerboseLevel::OFF);
                }
                return new VerboseLevel(\strtoupper(\trim($path)));
            }
        }
        throw new ConfigException("Verbose level in properties file is miss configured");
    }

    /**
     * Set the temp dir
     * @param string $path
     * @return $this
     * @throws ConfigException
     * @since 1.0.0
     */
    public function setTempDirectory($path)
    {
        if (!\is_string($path) || \trim($path) === "")
        {
            $msg = "Path must be an nonn empty string";
            \Logger::getLogger(\get_class($this))
                ->error(sprintf(__METHOD__ . " '%s'", $msg));
            throw new ConfigException($msg);
        }
        $this->ini[static::BLOCK_SYSTEM][static::KEY_TMP] = $path;
        \Logger::getLogger(\get_class($this))
            ->debug(
                sprintf(
                    __METHOD__ . " setted to '%s'",
                    $this->ini[static::BLOCK_SYSTEM][static::KEY_TMP]
                )
            );
        return $this;
    }

    /**
     *
     * Get the path of the temp directory to be used to write the temp files
     *
     * @return string The temp directory path
     * @throws ConfigException
     */
    public function getTempDirectory()
    {
        if (\array_key_exists(static::BLOCK_SYSTEM, $this->ini))
        {
            if (\array_key_exists(
                static::KEY_TMP,
                $this->ini[static::BLOCK_SYSTEM]
            ))
            {
                $tmp = $this->ini[static::BLOCK_SYSTEM][static::KEY_TMP];
                if (empty($tmp) === false)
                {
                    if (\is_writable($tmp) == false)
                    {
                        throw new ConfigException(
                            sprintf("Tmp file '%s' is not writable", $tmp)
                        );
                    }
                    return static::cleanLastSlash($tmp);
                }
            }
        }
        throw new ConfigException("Tmp file is not properly configured");
    }

    /**
     * Clean the las slash of string (to directory path)
     * @param string $string
     * @return string
     * @since 1.0.0
     */
    public static function cleanLastSlash($string)
    {
        $last = \substr($string, -1);
        $stk  = array(
            "\\",
            "/");
        if (\in_array($last, $stk))
        {
            return \substr($string, 0, strlen($string) - 1);
        }
        return $string;
    }

    /**
     * Configures the Log4php with the logconfig.xml in the Config folder
     * @return void
     */
    public static function configLog4Php()
    {
        if (static::$isSettedLogConfig === true)
        {
            return;
        }
        static::$isSettedLogConfig = true;

        $logxml = __DIR__ . DIRECTORY_SEPARATOR . "logconfig.xml";

        if (is_file($logxml))
        {
            \Logger::configure($logxml);
            return;
        }

        \Logger::getLogger(__CLASS__)->warn(
            sprintf(
                __METHOD__
                . " log4php log dosen't exist '%s'", $logxml
            )
        );
    }

}
