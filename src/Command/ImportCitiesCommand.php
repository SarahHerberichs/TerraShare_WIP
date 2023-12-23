<?php

namespace App\Command;

use App\Entity\Cities;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;


#[AsCommand(
    name: 'importCitiesCommand',
    description: 'Add a short description for your command',
)]
class ImportCitiesCommand extends Command
{
    
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        ini_set('memory_limit', '1024M'); // Augmentez la limite à 1 Go (ajustez selon vos besoins)

        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this->setDescription('Import cities from JSON file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Replace this with the actual path to your JSON file
        $jsonFilePath = 'src/data/cities.json';

        if (!file_exists($jsonFilePath)) {
            $io->error('JSON file not found.');
            return Command::FAILURE;
        }
        
        $jsonContent = file_get_contents($jsonFilePath);
        $data = json_decode($jsonContent, true);
        
        // Vérifiez si la clé "cities" existe dans le JSON
        if (isset($data['cities'])) {
            $citiesData = $data['cities'];
        
            foreach ($citiesData as $cityData) {
                $city = new Cities();
                $city->setName($cityData['city_code']);
                $city->setZipcode($cityData['zip_code']);
                $city->setDepartmentNumber($cityData['department_number']);
                // ... Set other fields if necessary
        
                $this->entityManager->persist($city);
            }
        
            $this->entityManager->flush();
            $io->success('Import successful!');
            return Command::SUCCESS;
        } else {
            $io->error('Invalid JSON format. Missing "cities" key.');
            return Command::FAILURE;
        }
    }
    // public function __construct()
    // {
    //     parent::__construct();
    // }

    // protected function configure(): void
    // {
    //     $this
    //         ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
    //         ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
    //     ;
    // }

    // protected function execute(InputInterface $input, OutputInterface $output): int
    // {
    //     $io = new SymfonyStyle($input, $output);
    //     $arg1 = $input->getArgument('arg1');

    //     if ($arg1) {
    //         $io->note(sprintf('You passed an argument: %s', $arg1));
    //     }

    //     if ($input->getOption('option1')) {
    //         // ...
    //     }

    //     $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

    //     return Command::SUCCESS;
    }

