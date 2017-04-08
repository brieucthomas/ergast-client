<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Configures the fixers, the level, the files, and directories that need to be analyzed.
 *
 * @see http://cs.sensiolabs.org/
 */

$fileHeaderComment = <<<COMMENT
(c) Brieuc Thomas <tbrieuc@gmail.com>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
COMMENT;

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('vendor')
;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'array_syntax' => ['syntax' => 'short'],
        'header_comment' => ['header' => $fileHeaderComment, 'separate' => 'both'],
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_imports' => true,
        'phpdoc_order' => true,
        'php_unit_strict' => true,
        'strict_comparison' => true,
    ])
    ->setFinder($finder)
;