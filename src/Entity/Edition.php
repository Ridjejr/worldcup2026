<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
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

    #[ORM\Column(type: "integer", name: "annee")]
    private ?int $annee = null;

    #[ORM\Column(type: "string", length: 50)]
    private ?string $nom = null;

    #[ORM\Column(type: "date", name: "date_debut")]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: "date", name: "date_fin")]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\OneToMany(mappedBy: "edition", targetEntity: Phase::class)]
    private $phases;

    public function __construct() { $this->phases = new \Doctrine\Common\Collections\ArrayCollection(); }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): static
    {
        $this->annee = $annee;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateDebut(): ?\DateTime
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTime $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTime
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTime $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * @return Collection<int, Phase>
     */
    public function getPhases(): Collection
    {
        return $this->phases;
    }

    public function addPhase(Phase $phase): static
    {
        if (!$this->phases->contains($phase)) {
            $this->phases->add($phase);
            $phase->setEdition($this);
        }

        return $this;
    }

    public function removePhase(Phase $phase): static
    {
        if ($this->phases->removeElement($phase)) {
            // set the owning side to null (unless already changed)
            if ($phase->getEdition() === $this) {
                $phase->setEdition(null);
            }
        }

        return $this;
    }
}
