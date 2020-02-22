<?php

namespace Ssch\T3Tactician\Tests\Unit\Command;

use LogicException;
use Ssch\T3Tactician\Tests\Unit\Fixtures\Command\DummyScheduledCommand;
use Nimut\TestingFramework\TestCase\UnitTestCase;

class AbstractScheduledCommandTest extends UnitTestCase
{
    /**
     * @test
     */
    public function setTimestamp()
    {
        $this->expectException(LogicException::class);
        $dummyCommand = new DummyScheduledCommand('email@domain.com', 'username');
        $dummyCommand->setTimestamp(1);
    }
}
