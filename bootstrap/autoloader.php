<?php

return function () {
    $files = [
        'composer_dependency' => __DIR__ . '/../../../autoload.php',
        'stand_alone' => __DIR__ . '/../vendor/autoload.php',
    ];

    foreach ($files as $file) {
        if (is_file($file)) {
            require_once $file;
            return true;
        }
    }

    return false;
};
