<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Uid\Uuid;

final class WorkingTimeSummaryDTO implements \JsonSerializable
{
    public function __construct(
        private readonly Uuid $id,
        private readonly float $standardHours,
        private readonly float $overtimeHours,
        private readonly int $salary,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'standardHours' => $this->standardHours,
            'overtimeHours' => $this->overtimeHours,
            'salary' => $this->salary . ' PLN',
        ];
    }
}
