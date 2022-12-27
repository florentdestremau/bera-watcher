<?php

namespace App\Command;

use App\Service\BeraExtractorService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:extract-daily-beras',
    description: 'Add a short description for your command',
)]
class ExtractDailyBerasCommand extends Command
{
    public function __construct(private BeraExtractorService $beraExtractorService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('date', InputArgument::OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $date = $input->getArgument('date') ? \DateTime::createFromFormat('Y-m-d', $input->getArgument('date')) : new \DateTime();
        $this->beraExtractorService->extract($date);


        return Command::SUCCESS;
    }
}
