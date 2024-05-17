<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\WorkingTimeSummaryDTO;
use App\Entity\WorkingTime;
use App\Repository\EmployeeRepositoryInterface;
use App\Repository\WorkingTimeRepositoryInterface;
use App\Request\GetWorkingTimeSummaryRequest;
use Symfony\Component\Uid\Uuid;

final class WorkingTimeSummaryProvider
{
    private const STANDARD_MONTH_HOURS = 40;
    private const STANDARD_RATE_PLN = 20;
    private const OVERTIME_RATE_MULTIPLIER = 2;

    public function __construct(
        private readonly WorkingTimeRepositoryInterface $workingTimeRepository,
        private readonly EmployeeRepositoryInterface $employeeRepository,
    ) {
    }

    public function provide(GetWorkingTimeSummaryRequest $workingTimeSummaryRequest)
    {
        $employeeId = Uuid::fromString($workingTimeSummaryRequest->getEmployeeId());
        $employee = $this->employeeRepository->findOneById($employeeId);

        $dateTimePattern = '/^\d{4}-\d{2}-\d{2}$/';
        if (preg_match($dateTimePattern, $workingTimeSummaryRequest->getDate())) {
            $workingTime = $this->workingTimeRepository->findByDateForEmployee(
                new \DateTimeImmutable($workingTimeSummaryRequest->getDate()),
                $employee
            );
            $workingTimes = $workingTime !== null ? [$workingTime] : [];
        } else {
            $workingTimes = $this->workingTimeRepository->findByYearAndMonthForEmployee(
                new \DateTimeImmutable($workingTimeSummaryRequest->getDate()),
                $employee
            );
        }

        $allHours = $this->countHours($workingTimes);
        if ($allHours > self::STANDARD_MONTH_HOURS) {
            $standardHours = self::STANDARD_MONTH_HOURS;;
            $overtimeHours = $allHours - self::STANDARD_MONTH_HOURS;
        } else {
            $standardHours = $allHours;
            $overtimeHours = 0;
        }

        $salary = $standardHours * self::STANDARD_RATE_PLN
            + $overtimeHours * self::STANDARD_RATE_PLN * self::OVERTIME_RATE_MULTIPLIER;

        return new WorkingTimeSummaryDTO(Uuid::v1(), $standardHours, $overtimeHours, (int)$salary);
    }

    /**
     * @param WorkingTime[] $workingTimes
     */
    private function countHours(array $workingTimes): float
    {
        $allHours = 0;
        foreach ($workingTimes as $workingTime) {
            $dateDiff = $workingTime->getStart()->diff($workingTime->getEnd());
            $allHours += $dateDiff->h;
            if ($dateDiff->i >= 30) {
                $allHours++;
            } elseif ($dateDiff->i >= 15) {
                $allHours += 0.5;
            }
        }
        return $allHours;
    }
}