<?php

namespace Ssch\T3Tactician\Tests\Unit\Factory;

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

use League\Tactician\CommandBus;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use Ssch\T3Tactician\Factory\CommandBusFactory;
use Ssch\T3Tactician\Middleware\MiddlewareHandlerResolverInterface;

class CommandBusFactoryTest extends UnitTestCase
{
    protected $subject;
    private $middlewareHandlerResolverMock;

    protected function setUp()
    {
        $this->middlewareHandlerResolverMock = $this->getMockBuilder(MiddlewareHandlerResolverInterface::class)->disableOriginalConstructor()->getMock();
        $this->subject = new CommandBusFactory($this->middlewareHandlerResolverMock);
    }

    /**
     * @test
     */
    public function returnsCommandBusInstance()
    {
        $this->middlewareHandlerResolverMock->method('resolveMiddlewareHandler')->willReturn([]);
        $this->assertInstanceOf(CommandBus::class, $this->subject->create());
    }
}
