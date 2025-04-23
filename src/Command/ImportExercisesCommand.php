<?php
// src/Command/ImportExercisesCommand.php

namespace App\Command;

use App\Entity\Exercice;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'exercise:import',
    description: 'Importe les exercices depuis un fichier JSON'
)]
class ImportExercisesCommand extends Command
{
    private $entityManager;
    private $jsonFilePath;

    public function __construct(EntityManagerInterface $entityManager, string $jsonFilePath)
    {
        $this->entityManager = $entityManager;
        $this->jsonFilePath = $jsonFilePath;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $json = file_get_contents($this->jsonFilePath);
        if ($json === false) {
            $io->error('Impossible de lire le fichier JSON. Vérifiez le chemin.');
            return Command::FAILURE;
        }

        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $io->error('Erreur lors du décodage du fichier JSON.');
            return Command::FAILURE;
        }

        foreach ($data as $item) {
            // Utilisez 'idExo' comme clé dans le JSON
        $idExo = $item['idExo'] ?? null;

        if (!$idExo) {
            $io->warning(sprintf("Le champ 'idExo' est manquant pour l'exercice '%s'. Ignoré.", $item['name'] ?? 'inconnu'));
            continue; // Ignorer cet exercice
        }

            $exercice = new Exercice();
            $exercice->setNom($item['name']);
            $exercice->setIdExo($idExo);
            $exercice->setBodyPart($item['bodyPart']);
            $exercice->setEquipment($item['equipment']);
            $exercice->setTarget($item['target']);
            $exercice->setSecondaryMuscles($item['secondaryMuscles']);
            $exercice->setInstructions($item['instructions']);
            $exercice->setGifUrl($item['gifUrl']);

            $this->entityManager->persist($exercice);
        }

        $this->entityManager->flush();
        $io->success('Exercices importés avec succès !');
        return Command::SUCCESS;
    }
}
?>