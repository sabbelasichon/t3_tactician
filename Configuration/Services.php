<?php

use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\ClassNameInflector;
use League\Tactician\Handler\MethodNameInflector\HandleClassNameInflector;
use League\Tactician\Handler\MethodNameInflector\HandleClassNameWithoutSuffixInflector;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\Handler\MethodNameInflector\InvokeInflector;
use League\Tactician\Plugins\LockingMiddleware;
use League\Tactician\Plugins\NamedCommand\NamedCommandExtractor;
use Ssch\T3Tactician\Command\DebugCommand;
use Ssch\T3Tactician\DependencyInjection\Compiler\CommandHandlerPass;
use Ssch\T3Tactician\DependencyInjection\Compiler\ValidatorMiddlewarePass;
use Ssch\T3Tactician\DependencyInjection\HandlerMapping\ClassNameMapping;
use Ssch\T3Tactician\DependencyInjection\HandlerMapping\CompositeMapping;
use Ssch\T3Tactician\DependencyInjection\HandlerMapping\TypeHintMapping;
use Ssch\T3Tactician\Middleware\LoggingMiddleware;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator, ContainerBuilder $containerBuilder): void {
    $services = $containerConfigurator->services();
    $services->defaults()
             ->private()
             ->autowire()
             ->autoconfigure();

    $services->load('Ssch\\T3Tactician\\', __DIR__.'/../Classes/')
    ->exclude([
        __DIR__ . '/../Classes/DependencyInjection'
    ]);

    $services->set(LockingMiddleware::class);
    $services->set(HandleInflector::class);
    $services->set(ClassNameInflector::class);
    $services->set(HandleClassNameInflector::class);
    $services->set(HandleClassNameWithoutSuffixInflector::class);
    $services->set(InvokeInflector::class);
    $services->set(ClassNameExtractor::class);
    $services->set(NamedCommandExtractor::class);
    $services->set(LoggingMiddleware::class);

    $containerBuilder->addCompilerPass(new ValidatorMiddlewarePass());
    $containerBuilder->addCompilerPass(new CommandHandlerPass(
            new CompositeMapping(new TypeHintMapping(), new ClassNameMapping()),
        )
    );

    $services->set(DebugCommand::class)->tag('console.command', [
        'command' => 't3_tactician:debug',
    ]);
};
