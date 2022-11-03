<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\Tests\Functional;

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
use Ssch\T3TacticianTest\Service\MyService;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

final class CommandBusTest extends FunctionalTestCase
{
    protected $initializeDatabase = false;

    protected $testExtensionsToLoad = [
        'typo3conf/ext/t3_tactician',
        'typo3conf/ext/t3_tactician/Tests/Functional/Fixtures/Extensions/t3_tactician_test',
    ];

    public function testThatCommandBusFooExists(): void
    {
        self::assertInstanceOf(CommandBus::class, $this->get('tactician.commandbus.foo'));
    }

    public function testThatCommandBusBarExists(): void
    {
        self::assertInstanceOf(CommandBus::class, $this->get('tactician.commandbus.bar'));
    }

    public function testThatCommandCanBeHandled(): void
    {
        self::assertSame('command.executed', $this->get(MyService::class)->handleFakeCommand());
    }
}
