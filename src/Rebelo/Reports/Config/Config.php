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

namespace Rebelo\Reports\Config;

use JetBrains\PhpStorm\Pure;

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
    public static string $iniPath = __DIR__ . "/config.properties";

    /**
     * @var \Logger
     * @since 3.0.0
     */
    public \Logger $log;

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
     * The reports API configuration block
     * @since 3.0.0
     */
    const BLOCK_API = "api";

    /**
     * The REST api endpoint URL
     * @since 3.0.0
     */
    const KEY_ENDPOINT = "endpoint";

    /**
     * The cache resources definition key
     * @since 3.0.0
     */
    const KEY_CACHE_RESOURCES = "cache_resources";

    /**
     *
     * Self instance
     *
     * Config
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
    protected array $ini = [];

    /**
     * Stores if log4php was already configured
     * @var boolean
     */
    protected static bool $isSetLogConfig = false;

    /**
     * @var bool
     * @since 3.0.0
     */
    protected bool $cacheResources = false;

    /**
     *
     * @throws ConfigException
     * @since 1.0.0
     */
    protected function __construct()
    {
        Config::configLog4Php();
        $this->log = \Logger::getLogger(__CLASS__);
        $this->log->debug(
            \sprintf(
                "ini file is going to be set to '%s'",
                static::$iniPath
            )
        );

        if (\is_file(static::$iniPath) === false || \is_readable(static::$iniPath) === false) {
            throw new ConfigException(
                \sprintf(
                    "ini file '%s' is not a file or is not readable",
                    static::$iniPath
                )
            );
        }

        $ini = \parse_ini_file(static::$iniPath, true);
        if ($ini === false) {
            throw new ConfigException(
                \sprintf(
                    "Error parsing ini file '%s'",
                    static::$iniPath
                )
            );
        }
        $this->ini = $ini;

        if (\array_key_exists(static::BLOCK_API, $this->ini)) {
            if (\array_key_exists(static::KEY_CACHE_RESOURCES, $this->ini[static::BLOCK_API])) {
                $cacheResources = $this->ini[static::BLOCK_API][static::KEY_CACHE_RESOURCES];
                if (\in_array(\strtolower($cacheResources), ["true", "yes", "1"])) {
                    $this->cacheResources = true;
                }
            }
        }
    }

    /**
     *
     * Get the instance of config
     *
     * @return Config
     * @since 1.0.0
     */
    public static function getInstance(): Config
    {
        if (static::$config !== null) {
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
    public function getJavaPath(): string
    {
        if (\array_key_exists(static::BLOCK_JAVA, $this->ini)) {
            if (\array_key_exists(static::KEY_JAVA_PATH, $this->ini[static::BLOCK_JAVA])) {
                $path = $this->ini[static::BLOCK_JAVA][static::KEY_JAVA_PATH];
                if (empty($path) === false) {
                    return $path;
                }
            }
        }
        throw new ConfigException("java path is not defined in the ini file");
    }

    /**
     * Set the Java Virtual Machine
     * @param string $path
     * @return \Rebelo\Reports\Config\Config
     * @throws ConfigException
     * @since 1.0.0
     */
    public function setJavaPath(string $path): Config
    {
        if ("" === $path = \trim($path)) {
            $msg = "Java Virtual Machine PAth must be a non empty string";
            $this->log->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new ConfigException($msg);
        }
        $this->ini[static::BLOCK_JAVA][static::KEY_JAVA_PATH] = $path;
        $this->log->debug(\sprintf(
            __METHOD__ . " set to '%s'",
            $this->ini[static::BLOCK_JAVA][static::KEY_JAVA_PATH]
        ));
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
    public function getJarPath(): string
    {
        if (\array_key_exists(static::BLOCK_JAVA, $this->ini)) {
            if (\array_key_exists(static::KEY_JAR_PATH, $this->ini[static::BLOCK_JAVA])) {
                $path = $this->ini[static::BLOCK_JAVA][static::KEY_JAR_PATH];
                if (empty($path) === false) {
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
     */
    public function getJavaXsharedClassesName(): ?string
    {
        if (\array_key_exists(static::BLOCK_JAVA, $this->ini)) {
            if (\array_key_exists(
                static::KEY_X_SHARECLASSE_NAME,
                $this->ini[static::BLOCK_JAVA]
            )) {
                if (empty($this->ini[static::BLOCK_JAVA][static::KEY_X_SHARECLASSE_NAME]) === false) {
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
     * @return string|null the X share classes dir or null if not configurated
     * @since 1.0.0
     */
    #[Pure] public function getJavaXsharedClassesDir(): ?string
    {
        if (\array_key_exists(static::BLOCK_JAVA, $this->ini)) {
            if (\array_key_exists(
                static::KEY_X_SHARECLASSE_DIR,
                $this->ini[static::BLOCK_JAVA]
            )) {
                if (empty($this->ini[static::BLOCK_JAVA][static::KEY_X_SHARECLASSE_DIR]) === false) {
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
     * @throws \Rebelo\Reports\Config\ConfigException
     * @throws \Rebelo\Enum\EnumException
     * @since 1.0.0
     */
    public function getVerboseLevel(): VerboseLevel
    {
        if (\array_key_exists(static::BLOCK_JAVA, $this->ini)) {
            if (\array_key_exists(static::KEY_VERBOSE, $this->ini[static::BLOCK_JAVA])) {
                $path = $this->ini[static::BLOCK_JAVA][static::KEY_VERBOSE];
                if (empty($path)) {
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
     * @return \Rebelo\Reports\Config\Config
     * @since 1.0.0
     */
    public function setTempDirectory(string $path): Config
    {
        if ("" === $path = \trim($path)) {
            $path = \sys_get_temp_dir();
        }
        $this->ini[static::BLOCK_SYSTEM][static::KEY_TMP] = $path;
        $this->log->debug(\sprintf(
            __METHOD__ . " set to '%s'",
            $this->ini[static::BLOCK_SYSTEM][static::KEY_TMP]
        ));
        return $this;
    }

    /**
     *
     * Get the path of the temp directory to be used to write the temp files
     *
     * @return string The temp directory path
     * @throws ConfigException
     */
    public function getTempDirectory(): string
    {
        if (\array_key_exists(static::BLOCK_SYSTEM, $this->ini)) {
            if (\array_key_exists(static::KEY_TMP, $this->ini[static::BLOCK_SYSTEM])) {
                $tmp = $this->ini[static::BLOCK_SYSTEM][static::KEY_TMP];
                if (empty($tmp) === false) {
                    if (\is_writable($tmp) == false) {
                        throw new ConfigException(
                            \sprintf("Tmp file '%s' is not writable", $tmp)
                        );
                    }
                    return static::cleanLastSlash($tmp);
                }
            }
        }
        return static::cleanLastSlash(\sys_get_temp_dir());
    }

    /**
     * Clean the last slash of string (to directory path)
     * @param string $string
     * @return string
     * @since 1.0.0
     */
    public static function cleanLastSlash(string $string): string
    {
        return \rtrim($string, " \t\n\r\0\x0B\\/");
    }

    /**
     * Configures the Log4php with the logconfig.xml in the Config folder
     * @return void
     */
    public static function configLog4Php(): void
    {
        if (static::$isSetLogConfig === true) {
            return;
        }
        static::$isSetLogConfig = true;

        $logxml = __DIR__ . DIRECTORY_SEPARATOR . "logconfig.xml";

        if (\is_file($logxml)) {
            \Logger::configure($logxml);
            return;
        }

        \Logger::getLogger(__CLASS__)->warn(
            \sprintf(__METHOD__ . " log4php log doesn't exist '%s'", $logxml)
        );
    }

    /**
     *
     * Get the java jvm path
     *
     * @return string
     * @throws ConfigException
     * @since 3.0.0
     */
    public function getApiEndpoint(): string
    {
        if (\array_key_exists(static::BLOCK_API, $this->ini)) {
            if (\array_key_exists(static::KEY_ENDPOINT, $this->ini[static::BLOCK_API])) {
                $endPoint = $this->ini[static::BLOCK_API][static::KEY_ENDPOINT];
                if (empty($endPoint) === false) {
                    return $endPoint;
                }
            }
        }
        throw new ConfigException("The api endpoint is not defined in the ini file");
    }

    /**
     * Set if resources are to cached.
     * Only used for API
     * @param bool $cacheResources
     * @return \Rebelo\Reports\Config\Config
     */
    public function setCacheResources(bool $cacheResources): Config
    {
        $this->cacheResources = $cacheResources;
        return $this;
    }

    /**
     * Get if resources are to cached.
     * Only used for API
     * @return bool
     * @since 3.0.0
     */
    public function getCacheResources(): bool
    {
        return $this->cacheResources;
    }
}
