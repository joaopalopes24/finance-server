<?php

namespace App\Enum;

enum OperationEnum: int
{
    case INCOME = 1;
    case EXPENSE = 2;

    public function label(): string
    {
        return match ($this) {
            self::INCOME => 'Income',
            self::EXPENSE => 'Expense',
        };
    }
}
