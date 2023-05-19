<?php
$rules = [
    '@PSR2' => true,
    'align_multiline_comment' => true,
    'array_syntax' => ['syntax' => 'short'],
    'binary_operator_spaces' => true,
    'blank_line_before_statement' => ['statements' => ['declare', 'do', 'for', 'foreach', 'if', 'switch', 'try']],
    'cast_spaces' => ['space' => 'none'],
    'class_attributes_separation' => true,
    'clean_namespace' => true,
    'combine_nested_dirname' => true, // risky
    'comment_to_phpdoc' => true, // risky
    'compact_nullable_typehint' => true,
    'concat_space' => ['spacing' => 'one'],
    'constant_case' => ['case' => 'lower'],
    'date_time_immutable' => true, // risky
    'declare_equal_normalize' => ['space' => 'none'],
    'declare_strict_types' => true, // risky
    'dir_constant' => true, // risky
    'ereg_to_preg' => true, // risky
    'final_class' => true, // risky
    'function_declaration' => [
        'closure_function_spacing' => 'none',
    ],
    'function_to_constant' => true, // risky
    'function_typehint_space' => true,
    'get_class_to_class_keyword' => true,
    'general_phpdoc_tag_rename' => true,
    'linebreak_after_opening_tag' => true,
    'list_syntax' => ['syntax' => 'short'],
    'lowercase_cast' => true,
    'lowercase_keywords' => true,
    'lowercase_static_reference' => true,
    'magic_constant_casing' => true,
    'magic_method_casing' => true,
    'mb_str_functions' => true, // risky
    'method_chaining_indentation' => true,
    'modernize_types_casting' => true, // risky
    'multiline_whitespace_before_semicolons' => false,
    'native_function_casing' => true,
    'native_function_type_declaration_casing' => true,
    'new_with_braces' => true,
    'no_alias_functions' => true, // risky
    'no_alias_language_construct_call' => true,
    'no_alternative_syntax' => true,
    'no_binary_string' => true,
    'no_blank_lines_after_class_opening' => true,
    'no_blank_lines_after_phpdoc' => true,
    'no_closing_tag' => true,
    'no_empty_comment' => true,
    'no_empty_phpdoc' => true,
    'no_empty_statement' => true,
    'no_extra_blank_lines' => [
        'tokens' => [
            'break',
            'continue',
            'curly_brace_block',
            'extra',
            'parenthesis_brace_block',
            'return',
            'square_brace_block',
            'throw',
            'use',
        ],
    ],
    'no_leading_import_slash' => true,
    'no_leading_namespace_whitespace' => true,
    'no_multiline_whitespace_around_double_arrow' => true,
    'no_null_property_initialization' => true,
    'no_php4_constructor' => true, // risky
    'no_short_bool_cast' => true,
    'no_singleline_whitespace_before_semicolons' => true,
    'no_space_around_double_colon' => true,
    'no_spaces_after_function_name' => true,
    'no_spaces_around_offset' => true,
    'no_spaces_inside_parenthesis' => true,
    'no_trailing_comma_in_list_call' => true,
    'no_trailing_comma_in_singleline_array' => true,
    'no_trailing_comma_in_singleline_function_call' => true,
    'no_trailing_whitespace' => true,
    'no_trailing_whitespace_in_comment' => true,
    'no_unneeded_control_parentheses' => true,
    'no_unneeded_import_alias' => true,
    'no_unused_imports' => true,
    'no_useless_else' => true,
    'no_useless_return' => true,
    'no_useless_sprintf' => true, // risky
    'no_whitespace_before_comma_in_array' => true,
    'no_whitespace_in_blank_line' => true,
    'non_printable_character' => true, // risky
    'normalize_index_brace' => true,
    'object_operator_without_whitespace' => true,
    'operator_linebreak' => [
        'position' => 'beginning'
    ],
    'ordered_class_elements' => true,
    'ordered_imports' => true,
    'php_unit_construct' => true, // risky
    'php_unit_dedicate_assert' => true, // risky
    'php_unit_dedicate_assert_internal_type' => true, // risky
    'php_unit_mock' => true, // risky
    'php_unit_namespaced' => true, // risky
    'php_unit_no_expectation_annotation' => true, // risky
    'php_unit_set_up_tear_down_visibility' => true, // risky
    'php_unit_strict' => true, // risky
    'php_unit_test_annotation' => true, // risky
    'php_unit_test_case_static_method_calls' => [ // risky
        'call_type' => 'this',
    ],
    'phpdoc_add_missing_param_annotation' => ['only_untyped' => false],
    'phpdoc_align' => [
        'align' => 'left',
    ],
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
    'phpdoc_order_by_value' => true,
    'phpdoc_return_self_reference' => true,
    'phpdoc_scalar' => true,
    'phpdoc_separation' => true,
    'phpdoc_single_line_var_spacing' => true,
    'phpdoc_summary' => true,
    'phpdoc_tag_type' => true,
    'phpdoc_to_comment' => true,
    'phpdoc_to_return_type' => true, // risky
    'phpdoc_trim' => true,
    'phpdoc_trim_consecutive_blank_line_separation' => true,
    'phpdoc_types' => true,
    'phpdoc_types_order' => [
        'sort_algorithm' => 'alpha',
        'null_adjustment' => 'always_last',
    ],
    'phpdoc_var_without_name' => true,
    'protected_to_private' => true,
    'psr_autoloading' => true, // risky
    'return_assignment' => true,
    'random_api_migration' => true, // risky
    'return_type_declaration' => ['space_before' => 'none'],
    'self_accessor' => true, // risky
    'self_static_accessor' => true,
    'semicolon_after_instruction' => true,
    'set_type_to_cast' => true, // risky
    'short_scalar_cast' => true,
    'simplified_if_return' => true,
    'single_blank_line_before_namespace' => true,
    'single_class_element_per_statement' => true,
    'single_import_per_statement' => true,
    'single_line_after_imports' => true,
    'single_line_comment_spacing' => true,
    'single_line_comment_style' => true,
    'single_quote' => true,
    'single_space_after_construct' => true,
    'space_after_semicolon' => true,
    'standardize_not_equals' => true,
    'static_lambda' => true, // risky
    'strict_comparison' => true, // risky
    'strict_param' => true, // risky
    'string_length_to_empty' => true, // risky
    'string_line_ending' => true, // risky
    'switch_case_semicolon_to_colon' => true,
    'switch_case_space' => true,
    'switch_continue_to_break' => true,
    'ternary_operator_spaces' => true,
    'ternary_to_null_coalescing' => true,
    'trailing_comma_in_multiline' => true,
    'trim_array_spaces' => true,
    'types_spaces' => true,
    'unary_operator_spaces' => true,
    'visibility_required' => true,
    'void_return' => true, // risky
    'whitespace_after_comma_in_array' => true,
    'yoda_style' => false,
];

$excludes = [
    'directories' => [
        'public',
        'storage',
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