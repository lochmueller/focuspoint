<?php

declare(strict_types=1);

$baseDir = dirname(__DIR__, 3);

require $baseDir.'/.Build/vendor/autoload.php';

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in($baseDir.'/Classes')
            ->in($baseDir.'/Configuration/TCA')
            ->in($baseDir.'/Configuration/Backend')
            ->in($baseDir.'/Tests')
            ->in($baseDir.'/Resources/Private/Build')
    )
    ->setRules([
        '@DoctrineAnnotation' => true,
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        '@PHP74Migration' => true,
        '@PHP74Migration:risky' => true,
    ])
;
