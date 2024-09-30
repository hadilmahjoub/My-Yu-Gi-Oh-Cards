<?php

namespace App\Command;

use App\Entity\YGOCard;
use App\Repository\YGOCardRepository;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\Persistence\ManagerRegistry;

#[AsCommand(
    name: 'app:list-ygo-cards',
    description: 'List all the YGO Cards',
)]
class ListYgoCardsCommand extends Command
{
    private YGOCardRepository $ygoCardRepository;
    
    public function __construct(ManagerRegistry $doctrineManager)
    {
        $this->ygoCardRepository = $doctrineManager->getRepository(YGOCard::class);
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            //->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            //->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ->setHelp('This command allows you to list the YGO Cards')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $ygoCards = $this->ygoCardRepository->findAll();

        if (!empty($ygoCards)) {
            $io->title('list of YGO Cards:');
            $io->listing($ygoCards);
        } else {
            $io->error('No YGO Cards found!');
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}
