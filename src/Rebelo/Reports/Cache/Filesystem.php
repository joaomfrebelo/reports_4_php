<?php

namespace Rebelo\Reports\Cache;

use Rebelo\Reports\Config\Config;

/**
 * @since 3.0.0
 */
class Filesystem implements ICache
{

    /**
     * Cache directory name inside tmp directory
     * @since 3.0.0
     */
    const DIR = "ResourceCache";

    /**
     * The cache hash
     * @var string
     * @since 3.0.0
     */
    private string $hash;

    /**
     * @var string
     * @since 3.0.0
     */
    private string $cacheFilePath;

    /**
     * The cache clas name
     * @var string
     * @since 3.0.0
     */
    private string $classCacheName;

    /**
     * @var \Logger
     * @since 3.0.0
     */
    protected \Logger $log;

    /**
     * Instance for cache of the resource path
     * @param string $path The resource path
     * @throws \Rebelo\Reports\Config\ConfigException
     * @throws \Rebelo\Reports\Cache\CacheException
     * @since 3.0.0
     */
    public function __construct(protected string $path)
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->init();
    }

    /**
     * Generate the hash
     * @param string $string
     * @return string
     * @since 3.0.0
     */
    public static function generateHash(string $string): string
    {
        return \hash(\PHP_VERSION_ID < 80100 ? 'sha256' : 'xxh128', $string);
    }

    /**
     * @param string $hash
     * @return string
     * @throws \Rebelo\Reports\Cache\CacheException
     * @throws \Rebelo\Reports\Config\ConfigException
     * @since 3.0.0
     */
    public static function generatePath(string $hash): string
    {
        return self::getCacheDirPath() . DIRECTORY_SEPARATOR . "Resource_" . $hash . ".php";
    }

    /**
     * @return string
     * @throws \Rebelo\Reports\Cache\CacheException
     * @throws \Rebelo\Reports\Config\ConfigException
     * @since 3.0.0
     */
    public static function getCacheDirPath(): string
    {
        $cacheDir = \join(DIRECTORY_SEPARATOR, [
            Config::getInstance()->getTempDirectory(), self::DIR,
        ]);

        if (\is_dir($cacheDir) === false) {
            if (\mkdir($cacheDir) === false) {
                $msg = \sprintf("Fail to create cache directory '%s'", $cacheDir);
                \Logger::getLogger(__CLASS__)->log->debug($msg);
                throw new CacheException($msg);
            }
        }

        return $cacheDir;
    }

    /**
     * Setup values
     * @return void
     * @throws \Rebelo\Reports\Cache\CacheException
     * @throws \Rebelo\Reports\Config\ConfigException
     */
    private function init(): void
    {

        $this->log->debug(
            \sprintf("Initiate cache for '%s'", $this->path)
        );
        $this->hash = self::generateHash($this->path);
        $this->log->debug(\sprintf("Cache hash: '%s'", $this->hash));

        $this->cacheFilePath = self::generatePath($this->hash);
        $this->log->debug(\sprintf("Cache file path set to '%s'", $this->cacheFilePath));

        $this->classCacheName = "\\" . (new \ReflectionClass($this))->getNamespaceName() . "\\Resource_" . $this->hash;
        $this->log->debug(\sprintf("Cache class name set to '%s'", $this->classCacheName));
    }

    /**
     * Load the class
     * @return bool True if cache exists and was load or false if not exist
     */
    protected function load(): bool
    {
        if (\class_exists($this->classCacheName, false)) {
            $this->log->info(\sprintf("The class '%s' already id loaded", $this->classCacheName));
            return true;
        }

        $opcache = \function_exists("opcache_is_script_cached")
                   && \opcache_is_script_cached($this->cacheFilePath);

        $msg = \sprintf(
            "The class '%s' in file '%s' of resource '%s'",
            $this->classCacheName,
            $this->cacheFilePath,
            $this->path,
        );

        $this->log->debug(
            \sprintf(
                "%s will try to be loaded from %s",
                $msg,
                $opcache ? "OPCache" : "Filesystem"
            )
        );

        if ($opcache || \is_file($this->cacheFilePath)) {
            require_once $this->cacheFilePath;
            return true;
        }

        $this->log->debug(
            \sprintf(
                "%s not exists, must be created",
                $msg
            )
        );

        return false;
    }

    /**
     * Create cache
     * @return void
     * @throws \Rebelo\Reports\Cache\CacheException
     */
    protected function create(): void
    {
        $templatePath = __DIR__ . DIRECTORY_SEPARATOR . "FilesystemCacheTemplate.tpl";

        if (false === $template = @\file_get_contents($templatePath)) {
            $msg = \sprintf("Fail to load cache template '%s'", $templatePath);
            $this->log->error($msg);
            throw new CacheException($msg);
        }

        if (false === $resource = @\file_get_contents($this->path)) {
            $msg = \sprintf("Fail to load resource file '%s'", $this->path);
            $this->log->error($msg);
            throw new CacheException($msg);
        }

        $cache = \str_replace(
            ['{RESOURCE_PATH}', '{RESOURCE_CLASS_NAME}', '{RESOURCE_BASE64}'],
            [
                $this->path,
                $this->hash,
                \base64_encode($resource),
            ],
            $template
        );

        $this->log->debug(
            \sprintf(
                "Going to create file cache '%s' for resource '%s'",
                $this->cacheFilePath,
                $this->path,
            )
        );

        \file_put_contents($this->cacheFilePath, $cache);

        unset($cache, $resource);

        if (false === $this->load()) {
            $msg = \sprintf("Fail to create cache resource for file '%s'", $this->path);
            $this->log->error($msg);
            throw new CacheException($msg);
        }
    }

    /**
     * Get the resource as base64 encoded string
     * @return string
     * @throws \Rebelo\Reports\Cache\CacheException
     */
    public function getResource(): string
    {
        $this->log->info(\sprintf("Get cache for resource '%s'", $this->path));

        if ($this->load() === false) {
            $this->create();
        }

        $class = $this->classCacheName;
        return (new $class)->getResource();
    }

    /**
     * Get the resource path
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Clear all cache
     * @return void
     * @throws \Rebelo\Reports\Config\ConfigException
     * @throws \Exception
     */
    public static function clearCache(): void
    {
        $dir = Config::getInstance()->getTempDirectory() . DIRECTORY_SEPARATOR . Filesystem::DIR;
        if (false === $scan = \scandir($dir)) {
            throw new \Exception("Fail to scan cache dir to delete old files");
        }

        foreach ($scan as $path) {
            if (\in_array($path, [".", ".."])) {
                continue;
            }
            \unlink($dir . DIRECTORY_SEPARATOR . $path);
        }
    }

    /**
     * Remove a resource from cache
     * @param string $resourcePath
     * @return void
     * @throws \Rebelo\Reports\Config\ConfigException
     * @throws \Rebelo\Reports\Cache\CacheException
     */
    public static function remove(string $resourcePath): void
    {
        $file  = self::generatePath(
            self::generateHash($resourcePath)
        );

        if (\is_file($file)) {
            \unlink($file);
        }
    }
}
