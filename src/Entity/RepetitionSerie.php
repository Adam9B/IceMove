<?php

namespace App\Entity;

use App\Repository\RepetitionSerieRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RepetitionSerieRepository::class)]
class RepetitionSerie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $repetition = null;

    #[ORM\Column]
    private ?int $serie = null;

    /**
     * Relation ManyToOne avec Sceance.
     */
    #[ORM\ManyToOne(targetEntity: Sceance::class, inversedBy: 'repetitionSeries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sceance $sceance = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRepetition(): ?int
    {
        return $this->repetition;
    }

    public function setRepetition(int $repetition): static
    {
        $this->repetition = $repetition;
        return $this;
    }

    public function getSerie(): ?int
    {
        return $this->serie;
    }

    public function setSerie(int $serie): static
    {
        $this->serie = $serie;
        return $this;
    }

    public function getSceance(): ?Sceance
    {
        return $this->sceance;
    }

    public function setSceance(?Sceance $sceance): static
    {
        $this->sceance = $sceance;
        return $this;
    }
}