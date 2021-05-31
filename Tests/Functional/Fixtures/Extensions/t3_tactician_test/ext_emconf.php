<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Test extension for Tactician command bus wrapper',
    'description' => 'Test extension for Tactician command bus wrapper',
    'category' => 'misc',
    'author' => 'Sebastian Schreiber',
    'author_email' => 'breakpoint@schreibersebastian.de',
    'state' => 'alpha',
    'createDirs' => '',
    'version' => '1.0.0',
    'autoload' => [
        'psr-4' => [
            'Ssch\\T3TacticianTest\\' => 'Classes',
        ],
    ],
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-9.5.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
