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


spl_autoload_register(function ($class)
{
    if (\strpos("\\", $class) === 0)
    {
        /** @var string Class name Striped of the first blackslash */
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
    if (is_file($path))
    {
        require_once $path;
    }
});

