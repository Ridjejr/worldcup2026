<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EquipeRepository;

#[ORM\Entity(repositoryClass: EquipeRepository::class)]
#[ORM\Table(name: "Equipe")]
class Equipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer", name: "Id_Equipe")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 50, name: "nom_equipe")]
    private ?string $nomEquipe = null;

    #[ORM\Column(type: "integer", name: "code_pays")]
    private ?int $codePays = null;

    #[ORM\Column(type: "string", length: 50)]
    private ?string $drapeau = null;

    #[ORM\ManyToOne(targetEntity: Groupe::class, inversedBy: "equipes")]
    #[ORM\JoinColumn(name: "Id_Groupe", referencedColumnName: "Id_Groupe", nullable: false)]
    private ?Groupe $groupe = null;

    #[ORM\ManyToMany(mappedBy: "equipes", targetEntity: WorldcupMatch::class)]
    private $matches;

    public function __construct() { $this->matches = new \Doctrine\Common\Collections\ArrayCollection(); }

    // Getters/Setters
    public function getId(): ?int { return $this->id; }
    public function getNomEquipe(): ?string { return $this->nomEquipe; }
    public function setNomEquipe(string $nomEquipe): self { $this->nomEquipe = $nomEquipe; return $this; }
    public function getCodePays(): ?int { return $this->codePays; }
    public function setCodePays(int $codePays): self { $this->codePays = $codePays; return $this; }
    public function getDrapeau(): ?string { return $this->drapeau; }
    public function setDrapeau(string $drapeau): self { $this->drapeau = $drapeau; return $this; }
    public function getGroupe(): ?Groupe { return $this->groupe; }
    public function setGroupe(Groupe $groupe): self { $this->groupe = $groupe; return $this; }
    public function getMatches() { return $this->matches; }
}
