<?php

namespace Rebelo\Reports\Cache;

/**
 * The resource cache class
 * @since 3.0.0
 */
interface ICache
{

    /**
     * Get the resource in cache, normal a base64 encoded string
     * @return string
     * @since 3.0.0
     */
    public function getResource(): string;

    /**
     * Get the resource file path
     * @return string
     * @since 3.0.0
     */
    public function getPath(): string;
}
