<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class GetWorkingTimeSummaryRequest
{
    #[Assert\Uuid]
    #[Assert\NotBlank]
    private mixed $employeeId;
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    private readonly mixed $date;

    private function __construct(mixed $employeeId, mixed $date)
    {
        $this->employeeId = $employeeId;
        $this->date = $date;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['employeeId'] ?? null,
            $data['date'] ?? null,
        );
    }

    public function getEmployeeId(): mixed
    {
        return $this->employeeId;
    }

    public function getDate(): mixed
    {
        return $this->date;
    }
}