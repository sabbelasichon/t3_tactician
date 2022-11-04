<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3TacticianTest\Service;

use League\Tactician\CommandBus;
use Ssch\T3Tactician\Tests\Unit\Fixtures\FakeCommand;
use Ssch\T3Tactician\Tests\Unit\Fixtures\NonHandledFakeCommand;

final class MyService
{
    private CommandBus $barBus;

    public function __construct(CommandBus $barBus)
    {
        $this->barBus = $barBus;
    }

    public function handleFakeCommand(): string
    {
        return $this->barBus->handle(new FakeCommand());
    }

    public function failToHandleCommand(): void
    {
        $this->barBus->handle(new NonHandledFakeCommand());
    }
}
