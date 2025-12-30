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

//  Elle lit un fichier .json avec plein d'exercices, et elle les met dans la base de données si ce ne sont pas des doublons.

// declare le nom de la commande et sa description
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
        
        // appelle le constructeur parent pour initialiser la commande
        // $entityManager est l'interface pour interagir avec la base de données
        // $jsonFilePath est le chemin du fichier JSON à importer
        // on utilise l'injection de dépendance pour passer l'EntityManager et le chemin
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->jsonFilePath = $jsonFilePath;
    }
    

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Vérifie que le fichier JSON existe sinon affiche une erreur
        if (!file_exists($this->jsonFilePath)) {
            $io->error("Le fichier JSON n'existe pas : {$this->jsonFilePath}");
            return Command::FAILURE;
        }

        // Lit le contenu du fichier JSON et le décode
        $json = file_get_contents($this->jsonFilePath);
        // convertit le JSON en tableau 
        $data = json_decode($json, true);


        // Vérifie si le JSON est valide
        if (!is_array($data)) {
            $io->error('Le fichier JSON est invalide ou vide.');
            return Command::FAILURE;
        }

        // fait un enregistrement tout les 50 exercices pour éviter de surcharger la mémoire
        $batchSize = 50;
        $count = 0;

        
        // si l'information idExo n'est pas présente, on ignore l'exercice
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

            // Si l'exercice existe déjà, on passe au suivant
            if ($existing) {
                continue;
            }

            // création du nouvel exercice avec les données du JSON
            $exercice = new Exercice();
            $exercice->setNom($item['name']);
            $exercice->setIdExo($idExo);
            $exercice->setBodyPart($item['bodyPart']);
            $exercice->setEquipment($item['equipment']);
            $exercice->setTarget($item['target']);
            $exercice->setSecondaryMuscles($item['secondaryMuscles']);
            $exercice->setInstructions($item['instructions']);
            $exercice->setGifUrl($item['gifUrl']);

            // on prepare l'entité pour l'enregistrement
            $this->entityManager->persist($exercice);

            // on envoie dans la base de données tout les 50 objets
            if (($count % $batchSize) === 0) {
                $this->entityManager->flush();
                $this->entityManager->clear(); // très important pour libérer la mémoire
            }

            // on incrémente le compteur
            $count++;
        }

        // on enregistre les derniers objets restants
        $this->entityManager->flush();
        // message de succès
        $io->success("$count exercices importés avec succès.");
        return Command::SUCCESS;
    }
}

?>