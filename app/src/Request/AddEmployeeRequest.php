<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class AddEmployeeRequest
{
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    private readonly mixed $name;
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    private readonly mixed $surname;

    private function __construct(mixed $name, mixed $surname)
    {
        $this->name = $name;
        $this->surname = $surname;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'] ?? null,
                $data['surname'] ?? null,
        );
    }

    public function getName(): mixed
    {
        return $this->name;
    }

    public function getSurname(): mixed
    {
        return $this->surname;
    }
}