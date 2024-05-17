<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\WorkingTime;

interface WorkingTimeRepositoryInterface
{
    /**
     * @return WorkingTime[]
     */
    public function findByYearAndMonthForEmployee(\DateTimeImmutable $date, Employee $employee): array;
}