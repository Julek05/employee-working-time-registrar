<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\WorkingTimeRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: WorkingTimeRepository::class)]
class WorkingTime
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $id;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $start;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $end;

    #[ORM\Column(type: Types::DATE_MUTABLE, unique: true)]
    private DateTimeInterface $startDay;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Employee $employee;

    public function __construct(
        DateTimeInterface $start,
        DateTimeInterface $end,
        DateTimeInterface $startDay,
        Employee $employee
    ) {
        $this->id = Uuid::v1();
        $this->start = $start;
        $this->end = $end;
        $this->startDay = $startDay;
        $this->employee = $employee;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getStart(): ?DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(DateTimeInterface $start): void
    {
        $this->start = $start;
    }

    public function getEnd(): ?DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(DateTimeInterface $end): void
    {
        $this->end = $end;
    }

    public function getStartDay(): ?DateTimeInterface
    {
        return $this->startDay;
    }

    public function setStartDay(DateTimeInterface $startDay): void
    {
        $this->startDay = $startDay;
    }

    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): void
    {
        $this->employee = $employee;
    }
}
