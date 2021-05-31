<?php

use Ssch\T3Tactician\Scheduler\Task\CommandTask;
use Ssch\T3Tactician\Command\ExecuteScheduledCommandsCommandController;
defined('TYPO3_MODE') || die('Access denied.');

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][CommandTask::class] = [
    'extension' => 't3_tactician',
    'title' => 'Execute command task',
    'description' => 'Execute command task',
];

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = ExecuteScheduledCommandsCommandController::class;
