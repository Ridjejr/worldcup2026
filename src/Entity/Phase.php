<?php

namespace App\Entity;

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

    // Getters/Setters
    public function getId(): ?int { return $this->id; }
    public function getLibelle(): ?string { return $this->libelle; }
    public function setLibelle(string $libelle): self { $this->libelle = $libelle; return $this; }
    public function getOrdre(): ?string { return $this->ordre; }
    public function setOrdre(string $ordre): self { $this->ordre = $ordre; return $this; }
    public function getEdition(): ?Edition { return $this->edition; }
    public function setEdition(?Edition $edition): self { $this->edition = $edition; return $this; }
    public function getMatches() { return $this->matches; }
    public function getGroupes() { return $this->groupes; }
}
