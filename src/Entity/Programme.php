<?php

namespace App\Entity;

use App\Repository\ProgrammeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'programmes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\OneToMany(mappedBy: 'programme', targetEntity: ProgrammeSceance::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $programmeSceances;

    public function __construct()
    {
        $this->programmeSceances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    /**
     * @return Collection<int, ProgrammeSceance>
     */
    public function getProgrammeSceances(): Collection
    {
        return $this->programmeSceances;
    }

    public function addProgrammeSceance(ProgrammeSceance $programmeSceance): self
    {
        if (!$this->programmeSceances->contains($programmeSceance)) {
            $this->programmeSceances[] = $programmeSceance;
            $programmeSceance->setProgramme($this);
        }

        return $this;
    }

    public function removeProgrammeSceance(ProgrammeSceance $programmeSceance): self
    {
        if ($this->programmeSceances->removeElement($programmeSceance)) {
            // Set the owning side to null (unless already changed)
            if ($programmeSceance->getProgramme() === $this) {
                $programmeSceance->setProgramme(null);
            }
        }

        return $this;
    }
}
