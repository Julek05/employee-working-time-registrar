<?php

declare(strict_types=1);

namespace App\Exception;

final class TooHighDateTimeRangeException extends \LogicException
{
    protected $message = 'Too high date time range';
}