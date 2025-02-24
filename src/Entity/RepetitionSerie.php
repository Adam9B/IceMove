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

    #[ORM\ManyToOne(inversedBy: 'repetitionSeries')]
    private ?sceance $sceance = null;

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

    public function getSceance(): ?sceance
    {
        return $this->sceance;
    }

    public function setSceance(?sceance $sceance): static
    {
        $this->sceance = $sceance;

        return $this;
    }
}
