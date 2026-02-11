<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupeRepository;

#[ORM\Entity(repositoryClass: GroupeRepository::class)]
#[ORM\Table(name: "Groupe")]
class Groupe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer", name: "Id_Groupe")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 50, name: "nom_groupe")]
    private ?string $nomGroupe = null;

    #[ORM\Column(type: "string", length: 50)]
    private ?string $classement = null;

    #[ORM\ManyToOne(targetEntity: Phase::class, inversedBy: "groupes")]
    #[ORM\JoinColumn(name: "Id_Phase", referencedColumnName: "Id_Phase", nullable: false)]
    private ?Phase $phase = null;

    #[ORM\OneToMany(mappedBy: "groupe", targetEntity: Equipe::class)]
    private $equipes;

    public function __construct() { $this->equipes = new \Doctrine\Common\Collections\ArrayCollection(); }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomGroupe(): ?string
    {
        return $this->nomGroupe;
    }

    public function setNomGroupe(string $nomGroupe): static
    {
        $this->nomGroupe = $nomGroupe;

        return $this;
    }

    public function getClassement(): ?string
    {
        return $this->classement;
    }

    public function setClassement(string $classement): static
    {
        $this->classement = $classement;

        return $this;
    }

    public function getPhase(): ?Phase
    {
        return $this->phase;
    }

    public function setPhase(?Phase $phase): static
    {
        $this->phase = $phase;

        return $this;
    }

    /**
     * @return Collection<int, Equipe>
     */
    public function getEquipes(): Collection
    {
        return $this->equipes;
    }

    public function addEquipe(Equipe $equipe): static
    {
        if (!$this->equipes->contains($equipe)) {
            $this->equipes->add($equipe);
            $equipe->setGroupe($this);
        }

        return $this;
    }

    public function removeEquipe(Equipe $equipe): static
    {
        if ($this->equipes->removeElement($equipe)) {
            // set the owning side to null (unless already changed)
            if ($equipe->getGroupe() === $this) {
                $equipe->setGroupe(null);
            }
        }

        return $this;
    }
}
