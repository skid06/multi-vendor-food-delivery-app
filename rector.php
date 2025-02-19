<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddArrayParamDocTypeRector;
use Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/bootstrap',
        __DIR__.'/config',
        __DIR__.'/public',
        __DIR__.'/resources',
        __DIR__.'/routes',
        __DIR__.'/tests',
    ])
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
        privatization: true,
        earlyReturn: true,
        strictBooleans: true,
    )
    ->withPhpSets();
//    ->withRules([
//        // Add specific rules for fixing the Larastan error
//        AddArrayParamDocTypeRector::class,
//    ])
//    ->withConfiguredRule(AddParamTypeDeclarationRector::class, [
//        new AddParamTypeDeclaration(
//            'App\Services\OrderService',
//            'calculateTotalAmount',
//            0, // Parameter position (0 for the first parameter)
//            'array' // Type to enforce
//        ),
//    ]);
