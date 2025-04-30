<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Caching\ValueObject\Storage\FileCacheStorage;

return RectorConfig::configure()

    ->withFileExtensions(['php'])
    
    ->withCache(
        cacheDirectory: __DIR__ . '/.rector.cache',
        cacheClass: FileCacheStorage::class
    )
    
    ->withPhpSets(
        php84: true
    )
    
    ->withPaths([
        __DIR__,
    ])
    
    ->withSkipPath(__DIR__ . '/vendor');
