<?php

defined('TYPO3_MODE') || die('Access denied.');

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Ssch\T3Tactician\Scheduler\Task\CommandTask::class] = [
    'extension' => 't3_tactician',
    'title' => 'Execute command task',
    'description' => 'Execute command task',
];

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = \Ssch\T3Tactician\Command\ExecuteScheduledCommandsCommandController::class;
