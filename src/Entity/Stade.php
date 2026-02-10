<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\StadeRepository;

#[ORM\Entity(repositoryClass: StadeRepository::class)]
#[ORM\Table(name: "Stade")]
class Stade
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer", name: "Id_Stade")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 50)]
    private ?string $nom = null;

    #[ORM\Column(type: "string", length: 50)]
    private ?string $ville = null;

    #[ORM\Column(type: "string", length: 50)]
    private ?string $pays = null;

    #[ORM\Column(type: "integer")]
    private ?int $capacite = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): static
    {
        $this->capacite = $capacite;

        return $this;
    }
}
