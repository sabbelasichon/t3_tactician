<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\Tests\Unit\Command;

use LogicException;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\DummyScheduledCommand;

class AbstractScheduledCommandTest extends UnitTestCase
{
    public function testSetTimestamp()
    {
        $this->expectException(LogicException::class);
        $dummyCommand = new DummyScheduledCommand('email@domain.com', 'username');
        $dummyCommand->setTimestamp(1);
    }
}
