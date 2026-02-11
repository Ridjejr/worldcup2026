<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\WorldcupMatchRepository;

#[ORM\Entity(repositoryClass: WorldcupMatchRepository::class)]
#[ORM\Table(name: "Match_")]
class WorldcupMatch
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer", name: "id_match")]
    private ?int $id = null;

    #[ORM\Column(type: "datetime", name: "date_heure")]
    private ?\DateTimeInterface $dateHeure = null;

    #[ORM\Column(type: "string", length: 50, name: "statut")]
    private ?string $statut = null;

    #[ORM\ManyToOne(targetEntity: Stade::class)]
    #[ORM\JoinColumn(name: "Id_Stade", referencedColumnName: "Id_Stade", nullable: false)]
    private ?Stade $stade = null;

    #[ORM\ManyToOne(targetEntity: Phase::class, inversedBy: "matches")]
    #[ORM\JoinColumn(name: "Id_Phase", referencedColumnName: "Id_Phase", nullable: false)]
    private ?Phase $phase = null;

    // 🔥 CORRECTION ICI : AJOUTÉ inversedBy: "matches"
    #[ORM\ManyToMany(targetEntity: Equipe::class, inversedBy: "matches")]
    #[ORM\JoinTable(name: "Participer",
        joinColumns: [new ORM\JoinColumn(name: "id_match", referencedColumnName: "id_match")],
        inverseJoinColumns: [new ORM\JoinColumn(name: "id_equipe", referencedColumnName: "id_equipe")]
    )]
    private Collection $equipes;

    #[ORM\OneToMany(mappedBy: "match", targetEntity: Participer::class, cascade: ["persist", "remove"])]
    private Collection $participations;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $apiFixtureId = null;

    public function __construct()
    {
        $this->equipes = new ArrayCollection();
        $this->participations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateHeure(): ?\DateTime
    {
        return $this->dateHeure;
    }

    public function setDateHeure(\DateTime $dateHeure): static
    {
        $this->dateHeure = $dateHeure;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    public function getStade(): ?Stade
    {
        return $this->stade;
    }

    public function setStade(?Stade $stade): static
    {
        $this->stade = $stade;
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
        }
        return $this;
    }

    public function removeEquipe(Equipe $equipe): static
    {
        $this->equipes->removeElement($equipe);
        return $this;
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
            $participation->setMatch($this);
        }
        return $this;
    }

    public function removeParticipation(Participer $participation): static
    {
        if ($this->participations->removeElement($participation)) {
            if ($participation->getMatch() === $this) {
                $participation->setMatch(null);
            }
        }
        return $this;
    }

    public function getApiFixtureId(): ?int
    {
        return $this->apiFixtureId;
    }

    public function setApiFixtureId(?int $apiFixtureId): static
    {
        $this->apiFixtureId = $apiFixtureId;
        return $this;
    }
}
