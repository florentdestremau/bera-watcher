<?php

namespace App\Model;

enum Weekday: string
{
    case MONDAY = 'LUN';
    case TUESDAY = 'MAR';
    case WEDNESDAY = 'MER';
    case THURSDAY = 'JEU';
    case FRIDAY = 'VEN';
    case SATURDAY = 'SAM';
    case SUNDAY = 'DIM';

    public static function fromNumber(int $number): Weekday
    {
        return match ($number) {
            1 => Weekday::MONDAY,
            2 => Weekday::TUESDAY,
            3 => Weekday::WEDNESDAY,
            4 => Weekday::THURSDAY,
            5 => Weekday::FRIDAY,
            6 => Weekday::SATURDAY,
            7 => Weekday::SUNDAY,
        };
    }

    public function number(): int
    {
        return match ($this) {
            Weekday::MONDAY    => 1,
            Weekday::TUESDAY   => 2,
            Weekday::WEDNESDAY => 3,
            Weekday::THURSDAY  => 4,
            Weekday::FRIDAY    => 5,
            Weekday::SATURDAY  => 6,
            Weekday::SUNDAY    => 7,
        };
    }
}
