<?php declare(strict_types = 1);

use Rector\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector;
use Rector\Config\RectorConfig;
use Rector\Php53\Rector\Ternary\TernaryToElvisRector;
use Rector\Php81\Rector\Array_\ArrayToFirstClassCallableRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\YieldDataProviderRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\ScalarArgumentToExpectedParamTypeRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/bin',
    ])
    ->withSkip([
        __DIR__ . '/tests/integration/MySql/Php/output',
        ArrowFunctionDelegatingCallToFirstClassCallableRector::class,
        ArrayToFirstClassCallableRector::class,
        // Fights ECS DisallowShortTernaryOperatorSniff (house style bans ?: / elvis)
        TernaryToElvisRector::class,
        // YieldDataProviderRector annotates providers with array<mixed>, banned by DisallowMixedTypeHintSniff
        YieldDataProviderRector::class,
        // Casts literals to wrong scalar types under declare(strict_types=1)
        ScalarArgumentToExpectedParamTypeRector::class,
    ])
    ->withPhpSets(php83: true)
    ->withPhpVersion(PhpVersion::PHP_83)
    ->withPreparedSets(deadCode: true)
    ->withSets([
        PHPUnitSetList::COMPOSER_BASED,
        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
        PHPUnitSetList::PHPUNIT_NARROW_ASSERTS,
    ]);
