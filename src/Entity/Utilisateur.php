<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Il y a déjà un compte avec cet email')]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Sceance::class, orphanRemoval: true)]
    private Collection $sceances;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Programme::class, orphanRemoval: true)]
    private Collection $programmes;

    public function __construct()
    {
        $this->sceances = new ArrayCollection();
        $this->programmes = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getEmail(): ?string { return $this->email; }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): ?string { return $this->password; }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void {}

    public function getSceances(): Collection { return $this->sceances; }

    public function addSceance(Sceance $sceance): static
    {
        if (!$this->sceances->contains($sceance)) {
            $this->sceances[] = $sceance;
            $sceance->setUtilisateur($this);
        }
        return $this;
    }

    public function removeSceance(Sceance $sceance): static
    {
        if ($this->sceances->removeElement($sceance)) {
            if ($sceance->getUtilisateur() === $this) {
                $sceance->setUtilisateur(null);
            }
        }
        return $this;
    }

    public function getProgrammes(): Collection { return $this->programmes; }

    public function addProgramme(Programme $programme): static
    {
        if (!$this->programmes->contains($programme)) {
            $this->programmes[] = $programme;
            $programme->setUtilisateur($this);
        }
        return $this;
    }

    public function removeProgramme(Programme $programme): static
    {
        if ($this->programmes->removeElement($programme)) {
            if ($programme->getUtilisateur() === $this) {
                $programme->setUtilisateur(null);
            }
        }
        return $this;
    }
}
