<?php

declare(strict_types=1);

namespace Ssch\T3TacticianTest\Service;


use League\Tactician\CommandBus;
use Ssch\T3TacticianTest\Command\RegisterUserCommand;

final class MyService
{
    public function __construct(private CommandBus $commandBus)
    {
    }

    public function handleCommand(): string
    {
        return $this->commandBus->handle(new RegisterUserCommand());
    }
}
