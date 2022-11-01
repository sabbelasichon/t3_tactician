<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

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
use Ssch\T3Tactician\Contract\CommandBusConfigurationInterface;
use Ssch\T3Tactician\Contract\FilesystemInterface;
use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Package\PackageManager;

final class CommandBusConfiguration implements CommandBusConfigurationInterface
{
    private array $middlewares = [];

    private array $commandHandlers = [];

    /**
     * @var string
     */
    private $inflector;

    public function __construct(private string $name, private PackageManager $packageManager, private FilesystemInterface $filesystem)
    {
        try {
            $this->initialize($name);
        } catch (Exception) {
        }
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

    private function initialize(string $name): void
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

    private function addCommandHandlers($commandBusConfiguration): void
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

    private function addMiddleware($commandBusConfiguration): void
    {
        if (is_array($commandBusConfiguration['middleware'])) {
            foreach ($commandBusConfiguration['middleware'] as $middleware) {
                $this->middlewares[] = $middleware;
            }
        }
    }

    private function addInflector($commandBusConfiguration): void
    {
        $this->inflector = $commandBusConfiguration['inflector'] ?? HandleInflector::class;
    }

    private function hasCommandHandler(string $command): bool
    {
        return array_key_exists($command, $this->commandHandlers);
    }
}
