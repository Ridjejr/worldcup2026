<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EditionRepository;

#[ORM\Entity(repositoryClass: EditionRepository::class)]
#[ORM\Table(name: "Edition")]
class Edition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer", name: "Id_Edition")]
    private ?int $id = null;

    #[ORM\Column(type: "date", name: "année")]
    private ?\DateTimeInterface $annee = null;

    #[ORM\Column(type: "string", length: 50)]
    private ?string $nom = null;

    #[ORM\Column(type: "date", name: "date_debut")]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: "date", name: "date_fin")]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\OneToMany(mappedBy: "edition", targetEntity: Phase::class)]
    private $phases;

    public function __construct() { $this->phases = new \Doctrine\Common\Collections\ArrayCollection(); }

    // Getters/Setters
    public function getId(): ?int { return $this->id; }
    public function getAnnee(): ?\DateTimeInterface { return $this->annee; }
    public function setAnnee(\DateTimeInterface $annee): self { $this->annee = $annee; return $this; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }
    public function getDateDebut(): ?\DateTimeInterface { return $this->dateDebut; }
    public function setDateDebut(\DateTimeInterface $dateDebut): self { $this->dateDebut = $dateDebut; return $this; }
    public function getDateFin(): ?\DateTimeInterface { return $this->dateFin; }
    public function setDateFin(\DateTimeInterface $dateFin): self { $this->dateFin = $dateFin; return $this; }
    public function getPhases() { return $this->phases; }
    public function addPhase(Phase $phase): self { if(!$this->phases->contains($phase)) { $this->phases[] = $phase; $phase->setEdition($this); } return $this; }
}
