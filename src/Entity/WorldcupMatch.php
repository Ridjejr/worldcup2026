<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\WorldcupMatchRepository;

#[ORM\Entity(repositoryClass: WorldcupMatchRepository::class)]
#[ORM\Table(name: "Match_")]
class WorldcupMatch
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer", name: "Id_Match")]
    private ?int $id = null;

    #[ORM\Column(type: "datetime", name: "date_heure")]
    private ?\DateTimeInterface $dateHeure = null;

    #[ORM\Column(type: "string", length: 50)]
    private ?string $status = null;

    #[ORM\ManyToOne(targetEntity: Stade::class)]
    #[ORM\JoinColumn(name: "Id_Stade", referencedColumnName: "Id_Stade", nullable: false)]
    private ?Stade $stade = null;

    #[ORM\ManyToOne(targetEntity: Phase::class, inversedBy: "matches")]
    #[ORM\JoinColumn(name: "Id_Phase", referencedColumnName: "Id_Phase", nullable: false)]
    private ?Phase $phase = null;

    #[ORM\ManyToMany(targetEntity: Equipe::class)]
    #[ORM\JoinTable(name: "Participer",
        joinColumns: [new ORM\JoinColumn(name: "Id_Match", referencedColumnName: "Id_Match")],
        inverseJoinColumns: [new ORM\JoinColumn(name: "Id_Equipe", referencedColumnName: "Id_Equipe")]
    )]
    private $equipes;

    public function __construct() { $this->equipes = new \Doctrine\Common\Collections\ArrayCollection(); }

    // Getters/Setters
    public function getId(): ?int { return $this->id; }
    public function getDateHeure(): ?\DateTimeInterface { return $this->dateHeure; }
    public function setDateHeure(\DateTimeInterface $dateHeure): self { $this->dateHeure = $dateHeure; return $this; }
    public function getStatus(): ?string { return $this->status; }
    public function setStatus(string $status): self { $this->status = $status; return $this; }
    public function getStade(): ?Stade { return $this->stade; }
    public function setStade(Stade $stade): self { $this->stade = $stade; return $this; }
    public function getPhase(): ?Phase { return $this->phase; }
    public function setPhase(Phase $phase): self { $this->phase = $phase; return $this; }
    public function getEquipes() { return $this->equipes; }
    public function addEquipe(Equipe $equipe): self { if(!$this->equipes->contains($equipe)) { $this->equipes[] = $equipe; } return $this; }
}
