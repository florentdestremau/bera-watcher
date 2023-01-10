<?php

namespace App\Command;

use App\Notifier\OnBeraExtractNotification;
use App\Repository\BeraRepository;
use App\Repository\SubscriberRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Notifier\NotifierInterface;

#[AsCommand(
    name: 'app:test-on-bera-extract',
    description: 'Add a short description for your command',
)]
class TestOnBeraExtractCommand extends Command
{
    public function __construct(
        private BeraRepository $beraRepository,
        private SubscriberRepository $subscriberRepository,
        private NotifierInterface $notifier,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sub = $this->subscriberRepository->findOneBy([]);
        $output->writeln("$sub");

        $bera = $this->beraRepository->findOneBy([]);
        $output->writeln("$bera");

        $this->notifier->send(new OnBeraExtractNotification($bera, ['email']), $sub);

        return Command::SUCCESS;
    }
}
