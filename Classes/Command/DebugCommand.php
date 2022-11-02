<?php

declare(strict_types=1);

/*
 * This file is part of the "t3_tactician" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Ssch\T3Tactician\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class DebugCommand extends Command
{
    private array $mappings;

    public function __construct(array $mappings = [])
    {
        parent::__construct();

        $this->mappings = $mappings;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Tactician routing');

        $headers = ['Command', 'Handler Service'];

        foreach ($this->mappings as $busId => $map) {
            $io->section('Bus: ' . $busId);

            if (count($map) > 0) {
                $io->table($headers, $this->mappingToRows($map));
            } else {
                $io->warning("No registered commands for bus {$busId}");
            }
        }

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setName('t3_tactician:debug');
    }

    private function mappingToRows(array $map): array
    {
        $rows = [];
        foreach ($map as $commandName => $handlerService) {
            $rows[] = [$commandName, $handlerService];
        }

        return $rows;
    }
}
