<?php

if (!function_exists('snakeToCamelCase')) {

    /**
     * @param string $input
     * @return string
     */
    function snakeToCamelCase(string $input) : string
    {
        return lcfirst(snakeToPascalCase($input));
    }
}

if (!function_exists('snakeToPascalCase')) {

    /**
     * @param string $input
     * @return string
     */
    function snakeToPascalCase(string $input) : string
    {
        $tokens = explode('_', $input);
        $tokens = array_map(function ($token) {
            return ucfirst($token);
        }, $tokens);

        return implode('', $tokens);
    }
}

if (!function_exists('camelToSnakeCase')) {

    /**
     * @param string $input
     * @return string
     */
    function camelToSnakeCase(string $input) : string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
}

if (!function_exists('basePath')) {

    /**
     * @param string|null $path
     * @return string
     */
    function basePath(?string $path = null) : string
    {
        $basePath = __DIR__ . '/../';

        if ($path !== null) {
            return $basePath . '/' . $path;
        }

        return $basePath;
    }
}
