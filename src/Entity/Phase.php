<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PhaseRepository;

#[ORM\Entity(repositoryClass: PhaseRepository::class)]
#[ORM\Table(name: "Phase")]
class Phase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer", name: "Id_Phase")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 50)]
    private ?string $libelle = null;

    #[ORM\Column(type: "string", length: 50)]
    private ?string $ordre = null;

    #[ORM\ManyToOne(targetEntity: Edition::class, inversedBy: "phases")]
    #[ORM\JoinColumn(name: "Id_Edition", referencedColumnName: "Id_Edition", nullable: false)]
    private ?Edition $edition = null;

    #[ORM\OneToMany(mappedBy: "phase", targetEntity: WorldcupMatch::class)]
    private $matches;

    #[ORM\OneToMany(mappedBy: "phase", targetEntity: Groupe::class)]
    private $groupes;

    public function __construct() { 
        $this->matches = new \Doctrine\Common\Collections\ArrayCollection(); 
        $this->groupes = new \Doctrine\Common\Collections\ArrayCollection(); 
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getOrdre(): ?string
    {
        return $this->ordre;
    }

    public function setOrdre(string $ordre): static
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getEdition(): ?Edition
    {
        return $this->edition;
    }

    public function setEdition(?Edition $edition): static
    {
        $this->edition = $edition;

        return $this;
    }

    /**
     * @return Collection<int, WorldcupMatch>
     */
    public function getMatches(): Collection
    {
        return $this->matches;
    }

    /**
     * @return Collection<int, Groupe>
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addMatch(WorldcupMatch $match): static
    {
        if (!$this->matches->contains($match)) {
            $this->matches->add($match);
            $match->setPhase($this);
        }

        return $this;
    }

    public function removeMatch(WorldcupMatch $match): static
    {
        if ($this->matches->removeElement($match)) {
            // set the owning side to null (unless already changed)
            if ($match->getPhase() === $this) {
                $match->setPhase(null);
            }
        }

        return $this;
    }

    public function addGroupe(Groupe $groupe): static
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes->add($groupe);
            $groupe->setPhase($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): static
    {
        if ($this->groupes->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getPhase() === $this) {
                $groupe->setPhase(null);
            }
        }

        return $this;
    }
}
