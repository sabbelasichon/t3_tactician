<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\Middleware;

use League\Tactician\Middleware;
use Psr\EventDispatcher\EventDispatcherInterface;
use Ssch\T3Tactician\Middleware\Event\CommandFailed;
use Ssch\T3Tactician\Middleware\Event\CommandHandled;
use Ssch\T3Tactician\Middleware\Event\CommandReceived;

final class EventMiddleware implements Middleware
{
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function execute($command, callable $next)
    {
        try {
            $this->eventDispatcher->dispatch(new CommandReceived($command));

            $returnValue = $next($command);

            $this->eventDispatcher->dispatch(new CommandHandled($command));

            return $returnValue;
        } catch (\Exception $e) {
            $event = new CommandFailed($command, $e);
            $this->eventDispatcher->dispatch($event);

            if (! $event->isExceptionCaught()) {
                throw $e;
            }

            return null;
        }
    }
}
