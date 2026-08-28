<?php declare(strict_types = 1);

use SlevomatCodingStandard\Sniffs\TypeHints\DeclareStrictTypesSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ParameterTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\PropertyTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ReturnTypeHintSniff;
use Symplify\EasyCodingStandard\Config\ECSConfig;

$base = require __DIR__ . '/vendor/kristijorgji/php-coding-standard/ecs/base.php';
$php85 = require __DIR__ . '/vendor/kristijorgji/php-coding-standard/ecs/php85.php';

$config = ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/bin',
        __DIR__ . '/bootstrap',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withRootFiles()
    ->withRules(array_merge($base['rules'], $php85['rules']))
    ->withSkip(array_merge($base['skip'], $php85['skip'], [
        // Generator output fixtures are compared byte-for-byte against raw
        // generator output and must NOT be reformatted.
        __DIR__ . '/tests/integration/MySql/Php/output',
        // Committed sample generated code.
        __DIR__ . '/docs',
        // Shebang file cannot take declare() on the first line.
        DeclareStrictTypesSniff::class => [__DIR__ . '/bin/dbToPhp'],
        // This library uses generic `array` config/fixture structures whose honest
        // element type is mixed, which DisallowMixedTypeHintSniff forbids. Requiring
        // item-level specs would force disallowed `mixed` or noisy pseudo-shapes, so
        // we keep native `array` type hints (MissingAnyTypeHint still enforced) but
        // relax the item-specification requirement.
        ReturnTypeHintSniff::class . '.MissingTraversableTypeHintSpecification',
        ParameterTypeHintSniff::class . '.MissingTraversableTypeHintSpecification',
        PropertyTypeHintSniff::class . '.MissingTraversableTypeHintSpecification',
    ]))
    ->withParallel()
    ->withCache(__DIR__ . '/.ecs_cache');

foreach (array_merge(
    $base['rulesWithConfiguration'],
    $php85['rulesWithConfiguration'],
) as $ruleClass => $configuration) {
    $config = $config->withConfiguredRule($ruleClass, $configuration);
}

return $config;
