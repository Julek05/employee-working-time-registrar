<?php

declare(strict_types=1);

namespace App\Repository;
use App\Entity\Employee;
use Symfony\Component\Uid\Uuid;

interface EmployeeRepositoryInterface
{
    public function findOneById(Uuid $id): ?Employee;
}