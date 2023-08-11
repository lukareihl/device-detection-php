<?php

$config = new PhpCsFixer\Config();

return $config
    ->setRules([
        '@PER' => true,
        '@PSR12' => true,
        '@Symfony' => true,
        '@PhpCsFixer' => true,
        'phpdoc_align' => false,
        'phpdoc_no_empty_return' => false,
        'phpdoc_separation' => false,
        'yoda_style' => false,
        'trailing_comma_in_multiline' => false,
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line'
        ],
        'concat_space' => [
            'spacing' => 'one'
        ],
        'not_operator_with_space' => false,
        'operator_linebreak' => [
            'position' => 'end',
            'only_booleans' => true
        ]
    ])
    ->setFinder(PhpCsFixer\Finder::create()
        ->in([
            __DIR__ . '/src'
        ])
    );