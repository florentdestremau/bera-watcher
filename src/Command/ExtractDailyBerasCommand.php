<?php

namespace App\Command;

use App\Entity\Bera;
use App\Event\BeraCreatedEvent;
use App\Model\Mountain;
use App\Repository\BeraRepository;
use App\Service\BeraWebExtractorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsCommand(
    name: 'app:extract-daily-beras',
    description: 'Add a short description for your command',
)]
class ExtractDailyBerasCommand extends Command
{
    public function __construct(
        private BeraWebExtractorService $webExtractorService,
        private EntityManagerInterface $entityManager,
        private BeraRepository $beraRepository,
        private EventDispatcherInterface $dispatcher,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('date', InputArgument::OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $date = $input->getArgument('date') ? \DateTime::createFromFormat('Y-m-d', $input->getArgument('date')) :
            new \DateTime();
        $date->setTime(0, 0);
        $mapping = $this->webExtractorService->extract($date);

        foreach ($mapping as $key => $hash) {
            $mountain = Mountain::from($key);
            $link = "https://donneespubliques.meteofrance.fr/donnees_libres/Pdf/BRA/BRA.{$key}.{$hash}.pdf";

            if (null === $this->beraRepository->findOneBy(['mountain' => $mountain, 'date' => $date])) {
                $bera = new Bera($mountain, $date, $link);
                $this->entityManager->persist($bera);
                $this->entityManager->flush();
                $output->writeln("Saved $bera");
                $this->dispatcher->dispatch(new BeraCreatedEvent($bera));
            }
        }

        return Command::SUCCESS;
    }
}
