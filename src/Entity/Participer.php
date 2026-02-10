<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ParticiperRepository;

#[ORM\Entity(repositoryClass: ParticiperRepository::class)]
#[ORM\Table(name: "participer")]
class Participer
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: WorldcupMatch::class, inversedBy: "participations")]
    #[ORM\JoinColumn(name: "id_match", referencedColumnName: "id_match")]
    private WorldcupMatch $match;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Equipe::class, inversedBy: "participations")]
    #[ORM\JoinColumn(name: "id_equipe", referencedColumnName: "id_equipe")]
    private Equipe $equipe;

    #[ORM\Column(type: "string", length: 10)]
    private string $role;

    #[ORM\Column(type: "boolean")]
    private bool $prolongation = false;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $tirAuBut = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $buts = null;

    public function getMatch(): ?WorldcupMatch
    {
        return $this->match;
    }

    public function setMatch(?WorldcupMatch $match): static
    {
        $this->match = $match;

        return $this;
    }

    public function getEquipe(): ?Equipe
    {
        return $this->equipe;
    }

    public function setEquipe(?Equipe $equipe): static
    {
        $this->equipe = $equipe;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getProlongation(): bool { return $this->prolongation; }

    public function setProlongation(bool $prolongation): static
    {
        $this->prolongation = $prolongation;

        return $this;
    }

    public function getTirAuBut(): ?int
    {
        return $this->tirAuBut;
    }

    public function setTirAuBut(?int $tirAuBut): static
    {
        $this->tirAuBut = $tirAuBut;

        return $this;
    }

    public function getButs(): ?int
    {
        return $this->buts;
    }

    public function setButs(?int $buts): static
    {
        $this->buts = $buts;

        return $this;
    }

    public function isProlongation(): ?bool
    {
        return $this->prolongation;
    }
}
