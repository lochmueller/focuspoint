<?php

declare(strict_types=1);
use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$baseDir = dirname(__DIR__, 3);

require $baseDir . '/.Build/vendor/autoload.php';

return (new Config())
    ->setRiskyAllowed(true)
    ->setFinder(
        Finder::create()
            ->in($baseDir . '/Classes')
            ->in($baseDir . '/Configuration')
            ->in($baseDir . '/Tests')
            ->in($baseDir . '/Resources/Private/Build')
    )
    ->setRules([
        '@PER-CS2.0' => true,
        '@PER-CS2.0:risky' => true,
        '@PHP81Migration' => true,
        '@PHP80Migration:risky' => true,
    ])
;
