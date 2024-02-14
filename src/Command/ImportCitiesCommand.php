<?php

namespace App\Command;

use App\Entity\Cities;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;

//Import des villes stockées dans fichier JSON
#[AsCommand(
    name: 'importCitiesCommand',
    description: 'Add a short description for your command',
)]
class ImportCitiesCommand extends Command
{
    
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        ini_set('memory_limit', '1024M'); 

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

    }

