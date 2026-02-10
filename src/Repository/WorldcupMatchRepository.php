<?php

namespace App\Repository;

use App\Entity\Phase;
use App\Entity\WorldcupMatch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WorldcupMatch>
 */
class WorldcupMatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorldcupMatch::class);
    }

    /**
     * Retourne les matchs filtrés par phase (ou tous si $phase = null)
     */
    public function findByPhase(?Phase $phase): array
    {
        $qb = $this->createQueryBuilder('m')
            ->leftJoin('m.phase', 'p')->addSelect('p')
            ->leftJoin('m.stade', 's')->addSelect('s')
            ->orderBy('m.dateHeure', 'ASC');

        if ($phase !== null) {
            $qb->andWhere('m.phase = :phase')
               ->setParameter('phase', $phase);
        }

        return $qb->getQuery()->getResult();
    }
}
