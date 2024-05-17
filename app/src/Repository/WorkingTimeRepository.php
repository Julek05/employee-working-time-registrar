<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\WorkingTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * @extends ServiceEntityRepository<WorkingTime>
 */
class WorkingTimeRepository extends ServiceEntityRepository implements WorkingTimeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkingTime::class);
    }

    public function findByYearAndMonthForEmployee(\DateTimeImmutable $date, Employee $employee): array
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.startDay BETWEEN :start AND :end')
            ->andWhere('w.employee = :employeeId')
            ->setParameter('start', $date->format('Y-m') . '-01')
            ->setParameter('end', ($date->modify('+1 month'))->format('Y-m') . '-01')
            ->setParameter('employeeId', $employee->getId(), UuidType::NAME)
            ->getQuery()
            ->getResult()
        ;
    }
}
