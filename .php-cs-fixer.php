<?php
/*
 * This document has been generated with
 * https://mlocati.github.io/php-cs-fixer-configurator/#version:3.8.0|configurator
 * you can change this configuration by importing this file.
 */
$config = new PhpCsFixer\Config();
return $config
    ->setRiskyAllowed(true)
    ->setRules([
        'array_indentation' => true,
        'array_syntax' => true,
        'assign_null_coalescing_to_coalesce_equal' => true,
        'binary_operator_spaces' => true,
        'braces' => true,
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'constant_case' => true,
        'declare_equal_normalize' => ['space' => 'single'],
        'elseif' => true,
        'empty_loop_body' => true,
        'empty_loop_condition' => true,
        'function_declaration' => true,
        'heredoc_indentation' => true,
        'heredoc_to_nowdoc' => true,
        'increment_style' => true,
        'indentation_type' => true,
        'integer_literal_case' => true,
        'is_null' => true,
        'lambda_not_used_import' => true,
        'line_ending' => true,
        'list_syntax' => true,
        'lowercase_cast' => true,
        'lowercase_keywords' => true,
        'lowercase_static_reference' => true,
        'native_function_casing' => true,
        'new_with_braces' => true,
        'no_binary_string' => true,
        'no_closing_tag' => true,
        'no_short_bool_cast' => true,
        'no_spaces_after_function_name' => true,
        'no_spaces_around_offset' => true,
        'no_spaces_inside_parenthesis' => true,
        'no_superfluous_elseif' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'no_trailing_comma_in_singleline_function_call' => true,
        'no_unneeded_control_parentheses' => true,
        'no_unneeded_curly_braces' => ['namespaces' => true],
        'no_unneeded_import_alias' => true,
        'no_unset_cast' => true,
        'no_unused_imports' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'no_whitespace_in_blank_line' => true,
        'normalize_index_brace' => true,
        'object_operator_without_whitespace' => true,
        'return_assignment' => true,
        'return_type_declaration' => true,
        'simplified_if_return' => true,
        'simplified_null_return' => true,
        'single_quote' => true,
        'space_after_semicolon' => true,
        'strict_comparison' => true,
        'switch_case_space' => true,
        'switch_continue_to_break' => true,
        'ternary_operator_spaces' => true,
        'ternary_to_null_coalescing' => true,
        'unary_operator_spaces' => true,
        'visibility_required' => true,
        'whitespace_after_comma_in_array' => true,
    ])
    ->setFinder(PhpCsFixer\Finder::create()
        ->in(__DIR__)
    )
;
