<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\WorkingTime;
use App\Exception\TooHighDateTimeRangeException;
use App\Repository\EmployeeRepositoryInterface;
use App\Request\RegisterWorkingTimeRequest;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

final class WorkingTimeSaver
{
    public function __construct(
        private readonly EntityManagerInterface $entityManagerInterface,
        private readonly EmployeeRepositoryInterface $employeeRepository
    ) {
    }

    public function save(RegisterWorkingTimeRequest $registerWorkingTimeRequest): void
    {
        $start = new DateTimeImmutable($registerWorkingTimeRequest->getStart());
        $end = new DateTimeImmutable($registerWorkingTimeRequest->getEnd());
        $startDay = new DateTimeImmutable($start->format('Y-m-d'));

        $diff = $start->diff($end);
        if (
            $start->format('Y-m') !== $end->format('Y-m')
            || $diff->days !== 0
            || $diff->h > 12
        ) {
            throw new TooHighDateTimeRangeException();
        }

        //TODO add validation only 1 date range with same start day

        $employee = $this->employeeRepository->findOneById(
            Uuid::fromString($registerWorkingTimeRequest->getEmployeeId())
        );

        $workingTime = new WorkingTime($start, $end, $startDay, $employee);

        $this->entityManagerInterface->persist($workingTime);
        $this->entityManagerInterface->flush();
    }
}