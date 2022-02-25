<?php

namespace Rebelo\Reports\Cache;

/**
 * Resource Path: {RESOURCE_PATH}
 */
class Resource_{RESOURCE_CLASS_NAME} implements ICache
{
    public function getResource(): string
    {
        return "{RESOURCE_BASE64}";
    }

    public function getPath(): string
    {
        return "{RESOURCE_PATH}";
    }
}
