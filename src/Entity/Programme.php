<?php

namespace App\Entity;

use App\Repository\ProgrammeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgrammeRepository::class)]
class Programme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'programmes')]
    #[ORM\JoinColumn(nullable: false)] // L'utilisateur est obligatoire
    private ?Utilisateur $utilisateur = null;

    /**
     * @var Collection<int, Sceance>
     */
    #[ORM\OneToMany(mappedBy: 'programme', targetEntity: Sceance::class, orphanRemoval: true)]
    private Collection $sceances;

   

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function __construct()
    {
        $this->sceances = new ArrayCollection();
    }

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

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

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
            $sceance->setProgramme($this); // Mettre à jour la relation bidirectionnelle
        }
        return $this;
    }

    public function removeSceance(Sceance $sceance): static
    {
        if ($this->sceances->removeElement($sceance)) {
            if ($sceance->getProgramme() === $this) {
                $sceance->setProgramme(null); // Mettre à jour la relation bidirectionnelle
            }
        }
        return $this;
    }

    

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }
}