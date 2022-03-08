<?php

$rules = [
    '@PSR2' => true,
    'array_syntax' => ['syntax' => 'short'],
    'binary_operator_spaces' => [
        'operators' => [
            '=>' => 'single_space',
            '=' => 'single_space',
        ],
    ],
    'compact_nullable_typehint' => true,
    'concat_space' => ['spacing' => 'one'],
    //'class_attributes_separation' => true, // ver.2.6.1 のバグを回避するため @see https://github.com/FriendsOfPHP/PHP-CS-Fixer/issues/4729
    'declare_equal_normalize' => ['space' => 'none'],
    'function_typehint_space' => true,
    'general_phpdoc_tag_rename' => true,
    'linebreak_after_opening_tag' => true,
    'list_syntax' => ['syntax' => 'short'],
    'lowercase_cast' => true,
    'magic_constant_casing' => true,
    'mb_str_functions' => true, // risky
    'modernize_types_casting' => true, // risky
    'multiline_whitespace_before_semicolons' => false,
    'native_function_casing' => true,
    'no_blank_lines_after_phpdoc' => true,
    //'no_empty_comment' => true,
    'no_empty_statement' => true,
    'no_extra_blank_lines' => [
        'tokens' => [
            'break',
            'continue',
            'curly_brace_block',
            //'extra',
            //'parenthesis_brace_block',
            'return',
            //'square_brace_block',
            'throw',
            //'use',
            'use_trait',
        ],
    ],
    'no_leading_import_slash' => true,
    'no_leading_namespace_whitespace' => true,
    'no_php4_constructor' => true,
    'no_singleline_whitespace_before_semicolons' => true,
    'no_spaces_around_offset' => true,
    'no_trailing_comma_in_list_call' => true,
    'no_trailing_comma_in_singleline_array' => true,
    'no_unneeded_control_parentheses' => true,
    //'no_unused_imports' => true,
    'no_useless_else' => true,
    'no_useless_return' => true,
    'no_whitespace_before_comma_in_array' => true,
    'no_whitespace_in_blank_line' => true,
    'non_printable_character' => true, // risky
    'normalize_index_brace' => true,
    'object_operator_without_whitespace' => true,
    'phpdoc_add_missing_param_annotation' => ['only_untyped' => false],
    'phpdoc_indent' => true,
    'phpdoc_inline_tag_normalizer' => true,
    'phpdoc_no_access' => true,
    'phpdoc_no_alias_tag' => [
        'replacements' => [
            'type' => 'var',
            'link' => 'see',
        ],
    ],
    'phpdoc_no_package' => true,
    'phpdoc_order' => true,
    'phpdoc_return_self_reference' => true,
    'phpdoc_scalar' => true,
    'phpdoc_single_line_var_spacing' => true,
    'phpdoc_tag_type' => true,
    'phpdoc_to_comment' => true,
    'phpdoc_trim' => true,
    'phpdoc_types' => true,
    'phpdoc_var_without_name' => true,
    'psr_autoloading' => true,
    'random_api_migration' => true, // risky
    'return_type_declaration' => ['space_before' => 'none'],
    'self_accessor' => true,
    'semicolon_after_instruction' => true,
    'short_scalar_cast' => true,
    'single_blank_line_before_namespace' => true,
    'single_line_comment_style' => true,
    'single_quote' => true,
    'space_after_semicolon' => true,
    'standardize_not_equals' => true,
    'strict_comparison' => true, // risky
    'strict_param' => true, // risky
    'ternary_operator_spaces' => true,
    'ternary_to_null_coalescing' => true,
    'trailing_comma_in_multiline' => true,
    'trim_array_spaces' => true,
    'unary_operator_spaces' => true,
    'whitespace_after_comma_in_array' => true,
    'yoda_style' => false,
];

$excludes = [
    'directories' => [
        'vendor',
    ],
    'path' => [
        '.php-cs-fixer.dist.php',
    ],
];

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude($excludes['directories']);
foreach ($excludes['path'] as $path) {
    $finder->notPath($path);
}

return (new PhpCsFixer\Config)
    ->setRules($rules)
    ->setRiskyAllowed(true)
    ->setCacheFile(__DIR__ . '/.php_cs.cache')
    ->setFinder($finder);