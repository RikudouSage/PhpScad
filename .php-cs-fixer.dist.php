<?php

use PhpCsFixer\Fixer\DoctrineAnnotation\DoctrineAnnotationBracesFixer;

$options = (new DoctrineAnnotationBracesFixer())
    ->getConfigurationDefinition()
    ->getOptions();

$ignoredTags = null;
foreach ($options as $option) {
    if($option->getName() === 'ignored_tags') {
        $ignoredTags = $option->getDefault();
        break;
    }
}

if ($ignoredTags === null) {
    throw new RuntimeException('Could not get list of default rules');
}

$ignoredTags[] = 'Annotation';
$ignoredTags[] = 'extends';
$ignoredTags[] = 'template';
$ignoredTags[] = 'implements';

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'blank_line_after_opening_tag' => true,
        'blank_line_before_statement' => [
            'statements' => ['declare', 'return', 'try'],
        ],
        'cast_spaces' => true,
        'class_attributes_separation' => true,
        'combine_consecutive_unsets' => true,
        'compact_nullable_typehint' => true,
        'concat_space' => [
            'spacing' => 'one',
        ],
        'doctrine_annotation_braces' => [
            'syntax' => 'with_braces',
            'ignored_tags' => $ignoredTags,
        ],
        'doctrine_annotation_spaces' => [
            'ignored_tags' => $ignoredTags,
        ],
        'doctrine_annotation_indentation' => [
            'ignored_tags' => $ignoredTags
        ],
        'explicit_indirect_variable' => true,
        'explicit_string_variable' => true,
        'final_class' => true,
        'fully_qualified_strict_types' => true,
        'function_typehint_space' => true,
        'include' => true,
        'linebreak_after_opening_tag' => true,
        'lowercase_cast' => true,
        'lowercase_static_reference' => true,
        'magic_constant_casing' => true,
        'magic_method_casing' => true,
        'multiline_comment_opening_closing' => true,
        'native_function_casing' => true,
        'no_alternative_syntax' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_empty_comment' => true,
        'no_empty_phpdoc' => true,
        'no_empty_statement' => true,
        'no_extra_blank_lines' => [
            'tokens' => [
                'extra',
                'break',
                'continue',
                'curly_brace_block',
                'parenthesis_brace_block',
                'return',
                'square_brace_block',
                'throw',
                'use',
                'use_trait',
                'switch',
                'case',
                'default',
            ],
        ],
        'no_leading_import_slash' => true,
        'no_leading_namespace_whitespace' => true,
        'no_mixed_echo_print' => true,
        'no_spaces_around_offset' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'no_unneeded_final_method' => true,
        'no_unused_imports' => true,
        'no_whitespace_before_comma_in_array' => true,
        'no_whitespace_in_blank_line' => true,
        'normalize_index_brace' => true,
        'object_operator_without_whitespace' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'protected_to_private' => true,
        'short_scalar_cast' => true,
        'single_blank_line_before_namespace' => true,
        'single_line_comment_style' => true,
        'single_quote' => true,
        'standardize_not_equals' => true,
        'ternary_operator_spaces' => true,
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays', 'arguments', 'parameters'],

        ],
        'unary_operator_spaces' => true,
        'visibility_required' => [
            'elements' => ['property', 'method', 'const'],
        ],
        'whitespace_after_comma_in_array' => true,
        'phpdoc_align' => true,
        'phpdoc_indent' => true,
        'phpdoc_no_package' => true,
        'phpdoc_order' => true,
        'phpdoc_scalar' => true,
        'phpdoc_separation' => true,
    ]);
