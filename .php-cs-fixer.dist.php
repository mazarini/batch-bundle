<?php

declare(strict_types=1);

/*
 * Copyright (C) 2025 Mazarini <mazarini@pm.me>.
 * This file is part of mazarini/batch-bundle.
 *
 * mazarini/batch-bundle is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or (at your
 * option) any later version.
 *
 * mazarini/batch-bundle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with mazarini/batch-bundle. If not, see <https://www.gnu.org/licenses/>.
 */

$fileHeaderComment = <<<COMMENT
Copyright (C) 2025 Mazarini <mazarini@pm.me>.
This file is part of mazarini/batch-bundle.

mazarini/batch-bundle is free software: you can redistribute it and/or
modify it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or (at your
option) any later version.

mazarini/batch-bundle is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
more details.

You should have received a copy of the GNU General Public License
along with mazarini/batch-bundle. If not, see <https://www.gnu.org/licenses/>.
COMMENT;

// ----------------------------------------------------
// Finder definition: which files and directories to scan
// ----------------------------------------------------
$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude([
        'config',
        'var',
        'vendor',
        'bin',
        'public/bundles',
        'public/build',
    ])
    ->notPath('public/index.php')
    ->notPath('importmap.php')
    ->notPath('tests/bootstrap.php')
    ->notPath('tests/app/Kernel.php')
    ->notPath('.php-cs-fixer.dist.php')
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

// ----------------------------------------------------
// Configuration: rules and options
// ----------------------------------------------------
return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true) // allow risky rules such as strict comparisons
    ->setRules([
        // Base Symfony rulesets
        '@Symfony'       => true,
        '@Symfony:risky' => true,

        // File header
        'header_comment' => [
            'header'   => $fileHeaderComment,
            'separate' => 'both',
            'location' => 'after_declare_strict',
        ],

        // Syntax and style
        'array_syntax'                => ['syntax' => 'short'],
        'concat_space'                => ['spacing' => 'one'],
        'linebreak_after_opening_tag' => true,
        'mb_str_functions'            => true,
        'single_quote'                => true,

        // Class & methods
        'no_php4_constructor'   => true,
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],

        // Control flow
        'no_unreachable_default_argument_value' => true,
        'not_operator_with_successor_space'     => true,
        'no_useless_else'                       => true,
        'no_useless_return'                     => true,
        'no_alternative_syntax'                 => true,
        'no_superfluous_elseif'                 => true,

        // Imports
        'ordered_imports'                  => ['sort_algorithm' => 'alpha'],
        'single_import_per_statement'      => true,
        'single_line_after_imports'        => true,
        'blank_line_between_import_groups' => false,

        // PHPUnit
        'php_unit_strict' => true,

        // PHPDoc
        'phpdoc_order'               => true,
        'phpdoc_align'               => ['align' => 'vertical'],
        'no_superfluous_phpdoc_tags' => true,
        'phpdoc_to_comment'          => true,

        // Language strictness
        'yoda_style'                 => false,
        'strict_comparison'          => true,
        'strict_param'               => true,
        'declare_strict_types'       => true,
        'native_constant_invocation' => ['scope' => 'all', 'strict' => true],
        'native_function_invocation' => [
            'include' => ['@all'],
            'scope'   => 'namespaced',
            'strict'  => true,
        ],

        // Formatting
        'trailing_comma_in_multiline' => ['elements' => ['arrays']],
        'no_extra_blank_lines'        => ['tokens' => ['extra']],
        'binary_operator_spaces'      => ['default' => 'align_single_space'],
        'cast_spaces'                 => ['space' => 'single'],
    ])
    ->setFinder($finder)
    ->setCacheFile(__DIR__ . '/var/cache/.php-cs-fixer');
