<?php

namespace App\Entity;

use App\Repository\SceanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SceanceRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Sceance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'sceances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToMany(targetEntity: Exercice::class, inversedBy: 'sceances')]
    private Collection $exercices;

    #[ORM\OneToMany(mappedBy: 'sceance', targetEntity: RepetitionSerie::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $repetitionSeries;

    #[ORM\OneToMany(mappedBy: 'sceance', targetEntity: ProgrammeSceance::class, orphanRemoval: true)]
    private Collection $programmeSceances;

    public function __construct()
    {
        $this->exercices = new ArrayCollection();
        $this->repetitionSeries = new ArrayCollection();
        $this->programmeSceances = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function setDateOnCreate(): void
    {
        if ($this->date === null) {
            $this->date = new \DateTime(); // Date du jour par défaut
        }
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;
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
     * @return Collection<int, Exercice>
     */
    public function getExercices(): Collection
    {
        return $this->exercices;
    }

    public function addExercice(Exercice $exercice): static
    {
        if (!$this->exercices->contains($exercice)) {
            $this->exercices->add($exercice);
            $exercice->addSceance($this);
        }
        return $this;
    }

    public function removeExercice(Exercice $exercice): static
    {
        if ($this->exercices->removeElement($exercice)) {
            $exercice->removeSceance($this);
        }
        return $this;
    }

    /**
     * @return Collection<int, RepetitionSerie>
     */
    public function getRepetitionSeries(): Collection
    {
        return $this->repetitionSeries;
    }

    public function addRepetitionSeries(RepetitionSerie $repetitionSeries): static
    {
        if (!$this->repetitionSeries->contains($repetitionSeries)) {
            $this->repetitionSeries->add($repetitionSeries);
            $repetitionSeries->setSceance($this);
        }
        return $this;
    }

    public function removeRepetitionSeries(RepetitionSerie $repetitionSeries): static
    {
        if ($this->repetitionSeries->removeElement($repetitionSeries)) {
            if ($repetitionSeries->getSceance() === $this) {
                $repetitionSeries->setSceance(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, ProgrammeSceance>
     */
    public function getProgrammeSceances(): Collection
    {
        return $this->programmeSceances;
    }

    public function addProgrammeSceance(ProgrammeSceance $programmeSceance): static
    {
        if (!$this->programmeSceances->contains($programmeSceance)) {
            $this->programmeSceances->add($programmeSceance);
            $programmeSceance->setSceance($this);
        }
        return $this;
    }

    public function removeProgrammeSceance(ProgrammeSceance $programmeSceance): static
    {
        if ($this->programmeSceances->removeElement($programmeSceance)) {
            if ($programmeSceance->getSceance() === $this) {
                $programmeSceance->setSceance(null);
            }
        }
        return $this;
    }
}
