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
    private EntityManagerInterface $entityManager;
    private string $jsonFilePath;

    public function __construct(EntityManagerInterface $entityManager, string $jsonFilePath)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->jsonFilePath = $jsonFilePath;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if (!file_exists($this->jsonFilePath)) {
            $io->error("Le fichier JSON n'existe pas : {$this->jsonFilePath}");
            return Command::FAILURE;
        }

        $json = file_get_contents($this->jsonFilePath);
        $data = json_decode($json, true);

        if (!is_array($data)) {
            $io->error('Le fichier JSON est invalide ou vide.');
            return Command::FAILURE;
        }

        $batchSize = 50;
        $count = 0;

        foreach ($data as $item) {
            $idExo = $item['idExo'] ?? null;
            if (!$idExo) {
                $name = $item['name'] ?? 'inconnu';
                $io->warning("Exercice sans idExo (nom: $name) ignoré.");
                continue;
            }

            // Évite les doublons si déjà en BDD
            $existing = $this->entityManager->getRepository(Exercice::class)
                ->findOneBy(['idExo' => $idExo]);

            if ($existing) {
                continue;
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

            if (($count % $batchSize) === 0) {
                $this->entityManager->flush();
                $this->entityManager->clear(); // très important pour libérer la mémoire
            }

            $count++;
        }

        $this->entityManager->flush();
        $io->success("$count exercices importés avec succès.");
        return Command::SUCCESS;
    }
}

?>