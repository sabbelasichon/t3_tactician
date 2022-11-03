<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Tactician command bus wrapper',
    'description' => 'Tactician command bus wrapper',
    'category' => 'misc',
    'author' => 'Sebastian Schreiber',
    'author_email' => 'breakpoint@schreibersebastian.de',
    'state' => 'beta',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '2.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-12.9.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Ssch\\T3Tactician\\' => 'Classes',
        ],
    ],
    'autoload-dev' => [
        'psr-4' => [
            'Ssch\\T3Tactician\\Tests\\' => 'Tests',
        ],
    ],
];
