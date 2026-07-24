<?php

if (! is_dir('/tmp/views')) {
    mkdir('/tmp/views', 0755, true);
}

if (! is_dir('/tmp/framework/cache')) {
    mkdir('/tmp/framework/cache', 0755, true);
}

if (! is_dir('/tmp/framework/sessions')) {
    mkdir('/tmp/framework/sessions', 0755, true);
}

$databaseConnection = getenv('DB_CONNECTION') ?: 'sqlite';

if ($databaseConnection === 'sqlite' && ! getenv('DB_DATABASE')) {
    $source = __DIR__.'/../database/database.sqlite';
    $target = '/tmp/database.sqlite';

    if (is_file($source) && ! is_file($target)) {
        copy($source, $target);
    }

    $_ENV['DB_DATABASE'] = $target;
    $_SERVER['DB_DATABASE'] = $target;
    putenv('DB_DATABASE='.$target);
}

require __DIR__.'/../public/index.php';
