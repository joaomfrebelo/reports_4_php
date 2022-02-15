<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */

require_once __DIR__
             . DIRECTORY_SEPARATOR . ".."
             . DIRECTORY_SEPARATOR . "vendor"
             . DIRECTORY_SEPARATOR . "autoload.php";

const TEST_DIR           = __DIR__;
const TEST_RESOURCES_DIR = TEST_DIR . DIRECTORY_SEPARATOR . "Resources";

define("TEST_CONFIG_PROP", \join(
    DIRECTORY_SEPARATOR,
    [__DIR__, "..", "src", "Rebelo", "Reports", "Config", "config.properties"]
));

spl_autoload_register(function ($class) {
    if (str_starts_with("\\", $class)) {
        /** @var string Class name Striped of the first backslash */
        $class = \substr($class, 1, \strlen($class) - 1);
    }

    $path = __DIR__
            . DIRECTORY_SEPARATOR
            . ".."
            . DIRECTORY_SEPARATOR
            . "src"
            . DIRECTORY_SEPARATOR
            . $class
            . ".php";
    if (is_file($path)) {
        require_once $path;
    }
});
