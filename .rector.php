<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\CodeQuality\Rector as CodeQuality;
use Rector\CodingStyle\Rector as CodingStyle;
use Rector\DeadCode\Rector as DeadCode;

return RectorConfig::configure()

    ->withFileExtensions(['php', 'phtml'])
    
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
    
    ->withSkipPath(__DIR__ . '/vendor')

    ->withRules([
        CodeQuality\BooleanNot\ReplaceMultipleBooleanNotRector::class,
        CodeQuality\Foreach_\UnusedForeachValueToArrayKeysRector::class,
        CodeQuality\FuncCall\ChangeArrayPushToArrayAssignRector::class,
        CodeQuality\FuncCall\CompactToVariablesRector::class,
        CodeQuality\FunctionLike\SimplifyUselessVariableRector::class,
        CodeQuality\Identical\SimplifyArraySearchRector::class,
        CodeQuality\Identical\SimplifyConditionsRector::class,
        CodeQuality\Identical\StrlenZeroToIdenticalEmptyStringRector::class,
        CodeQuality\NotEqual\CommonNotEqualRector::class,
        CodeQuality\LogicalAnd\LogicalToBooleanRector::class,
        CodeQuality\Ternary\SimplifyTautologyTernaryRector::class,
        CodingStyle\FuncCall\ConsistentImplodeRector::class,
        DeadCode\ClassMethod\RemoveUselessParamTagRector::class,
        DeadCode\ClassMethod\RemoveUselessReturnTagRector::class,
        DeadCode\Property\RemoveUselessVarTagRector::class,
        DeadCode\StaticCall\RemoveParentCallWithoutParentRector::class,
    ])

    ->withPreparedSets(
        deadCode: false,
        codeQuality: false,
        codingStyle: false,
        typeDeclarations: false,
        privatization: true,
        naming: false,
        instanceOf: false,
        earlyReturn: false,
        strictBooleans: false,
        carbon: false,
        rectorPreset: false,
        phpunitCodeQuality: true,
        doctrineCodeQuality: false,
        symfonyCodeQuality: false,
        symfonyConfigs: false,
    )
;
