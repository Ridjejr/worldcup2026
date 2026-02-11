<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EquipeRepository;

#[ORM\Entity(repositoryClass: EquipeRepository::class)]
#[ORM\Table(name: "Equipe")]
class Equipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer", name: "id_equipe")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 50, name: "nom_equipe")]
    private ?string $nomEquipe = null;

    #[ORM\Column(type: "string", length: 3, name: "code_pays")]
    private ?string $codePays = null;

    #[ORM\Column(type: "string", length: 50, name: "drapeau_url")]
    private ?string $drapeau = null;

    #[ORM\ManyToOne(targetEntity: Groupe::class, inversedBy: "equipes")]
    #[ORM\JoinColumn(name: "Id_Groupe", referencedColumnName: "Id_Groupe", nullable: false)]
    private ?Groupe $groupe = null;

    #[ORM\ManyToMany(mappedBy: "equipes", targetEntity: WorldcupMatch::class)]
    private $matches;
    #[ORM\OneToMany(mappedBy: "equipe", targetEntity: Participer::class)]
    private $participations;

    public function __construct() { $this->matches = new \Doctrine\Common\Collections\ArrayCollection();
    $this->participations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return Collection<int, Participer>
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participer $participation): static
    {
        if (!$this->participations->contains($participation)) {
            $this->participations->add($participation);
            $participation->setEquipe($this);
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEquipe(): ?string
    {
        return $this->nomEquipe;
    }

    public function setNomEquipe(string $nomEquipe): static
    {
        $this->nomEquipe = $nomEquipe;

        return $this;
    }

    public function getCodePays(): ?string
    {
        return $this->codePays;
    }

    public function setCodePays(string $codePays): static
    {
        $this->codePays = $codePays;

        return $this;
    }

    public function getDrapeau(): ?string
    {
        return $this->drapeau;
    }

    public function setDrapeau(string $drapeau): static
    {
        $this->drapeau = $drapeau;

        return $this;
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): static
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * @return Collection<int, WorldcupMatch>
     */
    public function getMatches(): Collection
    {
        return $this->matches;
    }

    public function addMatch(WorldcupMatch $match): static
    {
        if (!$this->matches->contains($match)) {
            $this->matches->add($match);
            $match->addEquipe($this);
        }

        return $this;
    }

    public function removeMatch(WorldcupMatch $match): static
    {
        if ($this->matches->removeElement($match)) {
            $match->removeEquipe($this);
        }

        return $this;
    }

    public function removeParticipation(Participer $participation): static
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getEquipe() === $this) {
                $participation->setEquipe(null);
            }
        }

        return $this;
    }
}
