<?php

return PhpCsFixer\Config::create()
    ->setUsingCache(true)
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        // contrib
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => ['align_double_arrow' => true, 'align_equals' => true],
        'concat_space' => ['spacing' => 'one'],
        'ordered_imports' => true,
        // symfony
        'blank_line_before_statement' => ['statements' => ['return']],
        'cast_spaces' => true,
        'function_typehint_space' => true,
        'include' => true,
        'no_alias_functions' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_extra_consecutive_blank_lines' => ['use'],
        'no_trailing_comma_in_list_call' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'no_unused_imports' => true,
        'no_whitespace_before_comma_in_array' => true,
        'phpdoc_align' => true,
        'phpdoc_indent' => true,
        'phpdoc_inline_tag' => true,
        'phpdoc_no_alias_tag' => ['type' => 'var'],
        'phpdoc_no_empty_return' => true,
        'phpdoc_scalar' => true,
        'phpdoc_summary' => true,
        'phpdoc_trim' => true,
        'phpdoc_types' => true,
        'phpdoc_var_without_name' => true,
        'simplified_null_return' => true,
        'single_blank_line_before_namespace' => true,
        'single_quote' => true,
        'ternary_operator_spaces' => true,
        'trailing_comma_in_multiline_array' => true,
        'trim_array_spaces' => true,
        'whitespace_after_comma_in_array' => true,
        // local
        '@PHP71Migration:risky' => true,
        'native_function_invocation' => ['include' => ['@compiler_optimized']]
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in([__DIR__ . '/src'])
    );
