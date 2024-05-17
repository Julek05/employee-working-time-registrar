<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class RegisterWorkingTimeRequest
{
    #[Assert\DateTime]
    #[Assert\NotBlank]
    private readonly mixed $start;

    #[Assert\DateTime]
    #[Assert\NotBlank]
    private readonly mixed $end;

    #[Assert\Uuid]
    #[Assert\NotBlank]
    private mixed $employeeId;

    private function __construct(mixed $start, mixed $end, mixed $employeeId)
    {
        $this->start = $start;
        $this->end = $end;
        $this->employeeId = $employeeId;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['start'] ?? null,
                $data['end'] ?? null,
                $data['employeeId'] ?? null,
        );
    }

    public function getStart(): mixed
    {
        return $this->start;
    }

    public function getEnd(): mixed
    {
        return $this->end;
    }

    public function getEmployeeId(): mixed
    {
        return $this->employeeId;
    }
}