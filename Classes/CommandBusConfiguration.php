<?php
declare(strict_types = 1);

namespace Ssch\T3Tactician;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use Ssch\T3Tactician\Integration\FilesystemInterface;
use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Package\PackageManager;

final class CommandBusConfiguration implements CommandBusConfigurationInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $middlewares = [];

    /**
     * @var array
     */
    private $commandHandlers = [];

    /**
     * @var PackageManager
     */
    private $packageManager;

    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * @var string
     */
    private $inflector;

    public function __construct(string $name, PackageManager $packageManager, FilesystemInterface $filesystem)
    {
        $this->packageManager = $packageManager;
        $this->filesystem = $filesystem;
        $this->name = $name;

        try {
            $this->initialize($name);
        } catch (Exception $e) {
        }
    }

    private function initialize(string $name)
    {
        foreach ($this->packageManager->getActivePackages() as $package) {
            $commandBusConfigurationFile = $package->getPackagePath() . 'Configuration/CommandBus.php';
            if ($this->filesystem->isFile($commandBusConfigurationFile)) {
                $commands = $this->filesystem->require($commandBusConfigurationFile);
                if (is_array($commands) && array_key_exists($name, $commands)) {
                    $commandBusConfiguration = $commands[$name];
                    $this->addCommandHandlers($commandBusConfiguration);
                    $this->addMiddleware($commandBusConfiguration);
                    $this->addInflector($commandBusConfiguration);
                }
            }
        }
    }

    private function addCommandHandlers($commandBusConfiguration)
    {
        if (is_array($commandBusConfiguration['commandHandler'])) {
            foreach ($commandBusConfiguration['commandHandler'] as $command => $handler) {
                if ($this->hasCommandHandler($command)) {
                    throw CommandAlreadyAssignedToHandlerException::commandAlreadyAssignedToHandler($command, $handler);
                }

                $this->commandHandlers[$command] = $handler;
            }
        }
    }

    private function addMiddleware($commandBusConfiguration)
    {
        if (is_array($commandBusConfiguration['middleware'])) {
            foreach ($commandBusConfiguration['middleware'] as $middleware) {
                $this->middlewares[] = $middleware;
            }
        }
    }

    private function addInflector($commandBusConfiguration)
    {
        $this->inflector = $commandBusConfiguration['inflector'] ?? HandleInflector::class;
    }

    private function hasCommandHandler(string $command): bool
    {
        return array_key_exists($command, $this->commandHandlers);
    }

    public function toString(): string
    {
        return $this->name;
    }

    public function middlewares(): array
    {
        return $this->middlewares;
    }

    public function commandHandlers(): array
    {
        return $this->commandHandlers;
    }

    public function inflector(): string
    {
        return $this->inflector ?: HandleInflector::class;
    }
}
