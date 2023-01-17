<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__.'/src')
//    ->in(__DIR__.'/tests')
    ->in(__DIR__.'/migrations')
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
        'concat_space' => false,
        'declare_strict_types' => true,
        'logical_operators' => true,
        'native_function_invocation' => true,
        'ordered_imports' => true,
        'phpdoc_align' => false,
        'phpdoc_order' => true,
        'phpdoc_summary' => false,
        'phpdoc_types_order' => true,
        'single_line_comment_style' => false,
        'single_line_throw' => false,
        'strict_comparison' => true,
        'yoda_style' => false,
        'no_superfluous_phpdoc_tags' => false,
        'no_unreachable_default_argument_value' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'linebreak_after_opening_tag' => true,
    ])
    ->setRiskyAllowed(true)
    ->setUsingCache(false)
    ->setFinder($finder)
;
