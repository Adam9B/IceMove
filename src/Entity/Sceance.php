<?php

namespace App\Entity;

use App\Repository\SceanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
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

    /**
     * @var Collection<int, RepetitionSerie>
     */
    #[ORM\OneToMany(targetEntity: RepetitionSerie::class, mappedBy: 'sceance')]
    private Collection $repetitionSeries;

    #[ORM\Column(length: 255)]
    private ?string $jour = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function __construct()
    {
        $this->repetitionSeries = new ArrayCollection();
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

    public function getProgramme(): ?programme
    {
        return $this->programme;
    }

    public function setProgramme(?programme $programme): static
    {
        $this->programme = $programme;

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
            // set the owning side to null (unless already changed)
            if ($repetitionSeries->getSceance() === $this) {
                $repetitionSeries->setSceance(null);
            }
        }

        return $this;
    }

    public function getJour(): ?string
    {
        return $this->jour;
    }

    public function setJour(string $jour): static
    {
        $this->jour = $jour;

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

  

 

    


}
