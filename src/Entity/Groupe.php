<?php

namespace App\Entity;

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

    // Getters/Setters
    public function getId(): ?int { return $this->id; }
    public function getNomGroupe(): ?string { return $this->nomGroupe; }
    public function setNomGroupe(string $nomGroupe): self { $this->nomGroupe = $nomGroupe; return $this; }
    public function getClassement(): ?string { return $this->classement; }
    public function setClassement(string $classement): self { $this->classement = $classement; return $this; }
    public function getPhase(): ?Phase { return $this->phase; }
    public function setPhase(Phase $phase): self { $this->phase = $phase; return $this; }
    public function getEquipes() { return $this->equipes; }
}
