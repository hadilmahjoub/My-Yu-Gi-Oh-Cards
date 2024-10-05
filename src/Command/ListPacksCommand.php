<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\PackRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Pack;

#[AsCommand(
    name: 'app:list-packs',
    description: 'Add a short description for your command',
)]
class ListPacksCommand extends Command
{
    private PackRepository $packRepository;
    
    public function __construct(ManagerRegistry $doctrineManager)
    {
        $this->packRepository = $doctrineManager->getRepository(Pack::class);
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
        ->setHelp('This command allows you to list all the available packs')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $packs = $this->packRepository->findAll();
        
        if (!empty($packs)) {
            $io->title('list of YGO Cards:');
            foreach ($packs as $pack) {
                $io->text('PACK '. $pack->getTitle()) ;
            }
            // $io->listing($packs);
        } else {
            $io->error('No Packs found!');
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}
