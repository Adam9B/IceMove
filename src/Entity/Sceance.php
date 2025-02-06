<?php

namespace App\Entity;

use App\Repository\SceanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SceanceRepository::class)]
class Sceance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\ManyToOne(inversedBy: 'sceances')]
    private ?programme $programme = null;

 

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getProgramme(): ?programme
    {
        return $this->programme;
    }

    public function setProgramme(?programme $programme): static
    {
        $this->programme = $programme;

        return $this;
    }

  

 

    


}
