<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\Middleware;

use League\Tactician\Handler\CommandHandlerMiddleware;
use Ssch\T3Tactician\CommandBusConfigurationInterface;
use Ssch\T3Tactician\CommandNameExtractor\HandlerExtractorInterface;
use Ssch\T3Tactician\HandlerLocator\HandlerLocatorInterface;
use Ssch\T3Tactician\MethodNameInflector\MethodNameInflectorInterface;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

class MiddlewareHandlerResolver implements MiddlewareHandlerResolverInterface
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function resolveMiddlewareHandler(CommandBusConfigurationInterface $commandBusConfiguration): array
    {
        $middleware = [];
        foreach ($commandBusConfiguration->middlewares() as $middlewareClass) {
            $middleware[] = $this->objectManager->get($middlewareClass);
        }

        // This is required, so put it at the end
        $middleware[] = new CommandHandlerMiddleware(
            $this->objectManager->get(HandlerExtractorInterface::class),
            $this->objectManager->get(HandlerLocatorInterface::class, $commandBusConfiguration),
            $this->objectManager->get(MethodNameInflectorInterface::class, $commandBusConfiguration)
        );

        return $middleware;
    }
}
