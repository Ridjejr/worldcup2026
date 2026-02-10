<?php

namespace App\Repository;

use App\Entity\Stade;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class StadeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stade::class);
    }

    // Exemple : méthode personnalisée
    public function findByVille(string $ville): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.ville = :ville')
            ->setParameter('ville', $ville)
            ->getQuery()
            ->getResult();
    }
}
