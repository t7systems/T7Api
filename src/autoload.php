<?php
function __autoload($class)
{
    $parts = explode('\\', $class);
    $class = implode(DIRECTORY_SEPARATOR, $parts);

    require __DIR__ . DIRECTORY_SEPARATOR . $class . '.php';
}