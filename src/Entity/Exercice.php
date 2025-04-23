<?php

namespace App\Entity;

use App\Repository\ExerciceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExerciceRepository::class)]
class Exercice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $bodyPart = null;

    #[ORM\Column(length: 255)]
    private ?string $equipment = null;

    #[ORM\Column(length: 255)]
    private ?string $gifUrl = null;

    #[ORM\Column(length: 255)]
    private ?string $idExo = null;

    #[ORM\Column(length: 255)]
    private ?string $target = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $secondaryMuscles = [];

    #[ORM\Column(type: Types::ARRAY)]
    private array $instructions = [];

    /**
     * @var Collection<int, Sceance>
     */
    #[ORM\ManyToMany(targetEntity: Sceance::class, mappedBy: 'exercices')]
    private Collection $sceances;

    public function __construct()
    {
        $this->sceances = new ArrayCollection();
    }

    // === Getters et Setters ===

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getBodyPart(): ?string
    {
        return $this->bodyPart;
    }

    public function setBodyPart(string $bodyPart): static
    {
        $this->bodyPart = $bodyPart;
        return $this;
    }

    public function getEquipment(): ?string
    {
        return $this->equipment;
    }

    public function setEquipment(string $equipment): static
    {
        $this->equipment = $equipment;
        return $this;
    }

    public function getGifUrl(): ?string
    {
        return $this->gifUrl;
    }

    public function setGifUrl(string $gifUrl): static
    {
        $this->gifUrl = $gifUrl;
        return $this;
    }

    public function getIdExo(): ?string
    {
        return $this->idExo;
    }

    public function setIdExo(string $idExo): static
    {
        $this->idExo = $idExo;
        return $this;
    }

    public function getTarget(): ?string
    {
        return $this->target;
    }

    public function setTarget(string $target): static
    {
        $this->target = $target;
        return $this;
    }

    public function getSecondaryMuscles(): array
    {
        return $this->secondaryMuscles;
    }

    public function setSecondaryMuscles(array $secondaryMuscles): static
    {
        $this->secondaryMuscles = $secondaryMuscles;
        return $this;
    }

    public function getInstructions(): array
    {
        return $this->instructions;
    }

    public function setInstructions(array $instructions): static
    {
        $this->instructions = $instructions;
        return $this;
    }

    // === Relations ===

    /**
     * @return Collection<int, Sceance>
     */
    public function getSceances(): Collection
    {
        return $this->sceances;
    }

    public function addSceance(Sceance $sceance): static
    {
        if (!$this->sceances->contains($sceance)) {
            $this->sceances->add($sceance);
            $sceance->addExercice($this); // Mettre à jour la relation inverse
        }
        return $this;
    }

    public function removeSceance(Sceance $sceance): static
    {
        if ($this->sceances->removeElement($sceance)) {
            $sceance->removeExercice($this); // Mettre à jour la relation inverse
        }
        return $this;
    }
}