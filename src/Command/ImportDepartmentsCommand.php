<?php

namespace App\Command;

use App\Entity\Departments;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

//Import des départements stockés dans fichier JSON

#[AsCommand(
    name: 'importDepartmentsCommand',
    description: 'Add a short description for your command',
)]
class ImportDepartmentsCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        ini_set('memory_limit', '1024M'); //Limit max

        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this->setDescription('Import departments from JSON file.');
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

        if (isset($data['cities'])) {
            $citiesData = $data['cities'];

            $departments = [];

            foreach ($citiesData as $cityData) {
                $departmentNumber = $cityData['department_number'];

                // Ajout du département s'il existe pas déjà
                if (!isset($departments[$departmentNumber])) {
                    $department = new Departments();
                    $department->setName($cityData['department_name']);
                    $department->setNumber($departmentNumber);

                    $this->entityManager->persist($department);

                    // Marque le dpt comme ajouté
                    $departments[$departmentNumber] = true;
                }
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

