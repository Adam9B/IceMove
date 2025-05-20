<?php

namespace App\Repository;

use App\Entity\ProgrammeSceance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProgrammeSceance>
 *
 * @method ProgrammeSceance|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProgrammeSceance|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProgrammeSceance[]    findAll()
 * @method ProgrammeSceance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgrammeSceanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProgrammeSceance::class);
    }

    // Exemple de méthode personnalisée
    public function findByProgrammeOrderedByJour($programmeId): array
    {
        

        return $this->createQueryBuilder('ps')
        ->innerJoin('ps.sceance', 's')
        ->addSelect('s')
        ->andWhere('ps.programme = :programmeId')
        ->setParameter('programmeId', $programmeId)
        ->orderBy('s.date', 'ASC') // ou un autre champ comme 'titre'
        ->getQuery()
        ->getResult();
    }
}