<?php
declare(strict_types = 1);

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

namespace Ssch\T3Tactician\Factory;

use League\Tactician\CommandBus;
use Ssch\T3Tactician\Middleware\MiddlewareHandlerResolverInterface;
use TYPO3\CMS\Core\SingletonInterface;

final class CommandBusFactory implements SingletonInterface
{
    private $middlewareHandlerResolver;

    public function __construct(MiddlewareHandlerResolverInterface $middlewareHandlerResolver)
    {
        $this->middlewareHandlerResolver = $middlewareHandlerResolver;
    }

    public function create(): CommandBus
    {
        return new CommandBus($this->middlewareHandlerResolver->resolveMiddlewareHandler());
    }
}
