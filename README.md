# TYPO3 Tactician
[![Build Status](https://img.shields.io/travis/sabbelasichon/t3_tactician/master.svg?style=flat-square)](https://travis-ci.org/sabbelasichon/t3_tactician)
[![Coverage Status](https://img.shields.io/coveralls/sabbelasichon/t3_tactician/master.svg?style=flat-square)](https://coveralls.io/github/sabbelasichon/t3_tactician?branch=master)

TYPO3 Extension for the Tactician library
[https://github.com/thephpleague/tactician/](https://github.com/thephpleague/tactician/)

## Installation

```bash
$ composer require ssch/t3-tactician
```

## Using the Command Bus

Create a class and inject the command bus factory:

```php
<?php

namespace Vendor\MyExtension\Controller;

use Ssch\T3Tactician\Factory\CommandBusFactoryInterface;
use Ssch\T3Tactician\Command\DummyCommand;

class YourNameController
{
    private $commandBus;

    public function __construct(CommandBusFactoryInterface $commandBus)
    {
        $this->commandBus = $commandBus->create();
    }

    public function doSomethingAction()
    {
        $command = new DummyCommand();
        $this->commandBus->handle($command);
    }
}
```

## Configuring Command Handlers
So, in order to handle your commands with a specific handler you have to configure it via TypoScript the following way:

```
config.tx_extbase {
    command_bus {
        default {
            commandHandler {
                Vendor\MyExtension\Command\MyCommand = Vendor\MyExtension\Handler\MyHandler
            }
        }
    }
}
```

## Middleware

The extension ships with a few pre-configured middlewares.
To enable them, add them to the middlewares list in your bus configuration via TypoScript:

```
config.tx_extbase {
    command_bus {
        default {
            middleware {
                Ssch\T3Tactician\Middleware\LoggingMiddleware = Ssch\T3Tactician\Middleware\LoggingMiddleware
            }
        }
    }
}
```

### ValidatorMiddleware
This middleware uses Extbase validator to check the command object before passing it along.

The validation rules can be added via annotations like in default Extbase practices.

If the command fails, it will throw a Ssch\T3Tactician\Middleware\InvalidCommandException.

### LoggingMiddleware
This middleware uses the TYPO3 Logging-API. This is useful especially during development.

### SchedulerMiddleware
This middleware allows you to create ScheduledCommands that will be executed at a specific time in the future.
Make sure you put the SchedulerMiddleware in your CommandBus middleware chain:

```
config.tx_extbase {
    command_bus {
        default {
            middleware {
                Ssch\T3Tactician\Middleware\SchedulerMiddleware = Ssch\T3Tactician\Middleware\SchedulerMiddleware
            }
        }
    }
}
```

The command you want to schedule must either extend from AbstractScheduledCommand or implement the ScheduledCommandInterface.
If you did so create your command and set your desired execution time:

```php
<?php

namespace Vendor\MyExtension\Command;

use Ssch\T3Tactician\Command\AbstractScheduledCommand;

class YourScheduledCommand extends AbstractScheduledCommand
{
    private $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
```

This command will be stored in the database table tx_scheduler_task.

The real execution has to be executed via a extbase commandController task called "t3_tactician:executescheduledcommands:executescheduledcommands"

### Custom middleware
You can also create your own middleware and add them to the configuration.
The ordering in the configuration is important.

The very last middleware per default is the Command Handler Middleware bundled in Tactician.
This is the plugin that actually matches your command to a handler and executes it.

If you really need to customize this, feel free to contact me. Actually you can. But this is not part of the documentation.

## Customizing the MethodNameInflector

By default, the extension uses `HandleInflector` from Tactician core. That is to say, it expects your Command Handlers to have a `handle()` method that receives the command to execute.

However, [if you prefer a different inflector](http://tactician.thephpleague.com/tweaking-tactician/), you can override this via TypoScript configuration:

```
config.tx_extbase {
    command_bus {
        default {
            method_inflector = League\Tactician\Handler\MethodNameInflector\InvokeInflector
        }
    }
}
```

Now your command handlers have to implement the __invoke method.

## Unit Testing
``` bash
$ .Build/bin/phpunit --colors -c .Build/vendor/nimut/testing-framework/res/Configuration/UnitTests.xml Tests/Unit/
```
