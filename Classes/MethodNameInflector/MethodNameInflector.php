<?php
declare(strict_types=1);


namespace Ssch\T3Tactician\ClassNameInflector;

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

use League\Tactician\Handler\MethodNameInflector\HandleClassNameInflector;

final class MethodNameInflector implements MethodNameInflectorInterface
{

    private $inflector;

    /**
     * MethodNameInflector constructor.
     *
     * @param $inflector
     */
    public function __construct(HandleClassNameInflector $inflector)
    {
        $this->inflector = $inflector;
    }


    /**
     * Return the method name to call on the command handler and return it.
     *
     * @param object $command
     * @param object $commandHandler
     *
     * @return string
     */
    public function inflect($command, $commandHandler): string
    {
        return $this->inflector->inflect($command, $commandHandler);
    }
}
